<?php

function sanitizeString($_db, $str)
{
    $str = strip_tags($str);
    $str = htmlentities($str);
    $str = stripslashes($str);
    return mysqli_real_escape_string($_db, $str);
}


//inserts image name and other values into wall
function SavePostToDB($_db, $_user, $_title, $_text, $_time, $_file_name, $_folder)
{
            $insertStmt = "INSERT INTO WALL (USERNAME, TITLE, TEXT, TIME_STAMP, IMAGE_NAME, FNAME)
                  VALUES ('$_user', '$_title', '$_text', '$_time', '$_file_name', '$_folder')";
    
                  if($_db->query($insertStmt)) {
                    } 
                  else 
                  {
                    echo '        <div class="alert alert-danger">Value insertion failed: (' . $db->errno . ') ' . $db->error . '</div>' . PHP_EOL;
                    exit();
                 }
}

//gets folders user is subscribed to and puts them into a drop-down menu
function dropdown($_db, $_user)
{
    $query =    "SELECT FNAME
                FROM FOLDER
                WHERE USERNAME = '$_user'";
    $result = $_db->query($query);
    
    if(!$result)
    {
        die('There was an error running the query [' . $_db->error . ']');
    }
    
    $output1 = '';
    while($row = $result->fetch_assoc())
    {
        $value = $row['FNAME'];
        if ($value == "public")
        {
            $output1 ='<option value="'. $value .'" selected>'. ucfirst($value) .'</option>'. $output1;
        }
        else if ($value == "private")
        {
            $output1 ='<option value="'. $value .'">'. ucfirst($value).'</option>'. $output1;
        }
        else {
            $output1 = $output1 . '<option value="'. ucfirst($value) .'">'. $value .'</option>';
        }
    }
    return $output1;
}


//gets and displays all folders the user is subscribed too.
function folder ($_db, $_user)
{
    $query =    "SELECT FNAME
                FROM FOLDER
                WHERE USERNAME = '$_user'";
    $result = $_db->query($query);
    
    if(!$result)
    {
        die('There was an error running the query [' . $_db->error . ']');
    }
    
    $output1 = '';
    while($row = $result->fetch_assoc())
    {
        $value = $row['FNAME'];
        if ($value == "public" || $value == "private")
        {
            $output1 ='<div class="fder1"  id = "'. $value .'" ><div class="txt11">'. ucfirst($value) .' </div></div>'. $output1;
        }
        else {
            $output1 = $output1 . '<div class="fder1"><div class="txt11">'. ucfirst($value) .' <i id = "'. $value .'" class="fa fa-minus fa-lg minuss"></i></div></div>';
        }
    }
    return $output1;
}

//gets images to show up on feed
function getPostcards($_db,$_user, $fd = "public")
{
    
    $query1 = "SELECT USERNAME, TITLE, TEXT, TIME_STAMP, IMAGE_NAME FROM WALL W WHERE W.FNAME = '".$fd."' AND W.USERNAME = '".$_user."' ORDER BY TIME_STAMP DESC";
    
     $query2 = "SELECT USERNAME, TITLE, TEXT, TIME_STAMP, IMAGE_NAME FROM WALL W WHERE W.FNAME = '".$fd."' ORDER BY TIME_STAMP DESC";
    
    //checks if its private or not
    if ($fd == "private")
    {
        $result = $_db->query($query1);
    }
    else
    {
        $result = $_db->query($query2);
    }
    
    if(!$result)
    {
        die('There was an error running the query [' . $_db->error . ']');
    }
    //string to save all images and echo it on to the feed page
    $output = '';
    if($result->num_rows > 0)
    {

        while($row = $result->fetch_assoc())
        {
            
            $us = $row['USERNAME'];
            //checks if its your inage
            //if it is then you can delete it
            if ($us == $_user)
            {
                $output = $output . '<div class="centerz"><div class="bord"><div class="panel-heading"><h2 style="line-height:13px">' . $row['TITLE']. '<span style="font-size: 45%"> <br> posted by ' . $us . '</span></h2></div><div><img src="images/' . $row['IMAGE_NAME'] . '" width="680px" class="img" ></div><div class="txt">' .$row['TEXT'] . '</div><button type="button" class="alala btn btn-danger" id= "'.$row['IMAGE_NAME'].'"><i class="fas fa-trash-alt" style="margin-bottom:5px"></i></button></div></div>';               
            }
            else {
                $output = $output . '<div class="centerz"><div class="bord"><div class="panel-heading"><h2 style="line-height:13px">' . $row['TITLE']
                . '<span style="font-size: 45%"> <br> posted by ' . $us . '</span></h2></div><div><img src="images/' . $row['IMAGE_NAME'] . '" width="680px" class="img" ></div><div class="txt">' .$row['TEXT'] . '</div></div></div>';
            }

        }
    }
    else
    {
        $output = 'No images currently uploaded';
    }
    
    return $output;
}
?>
