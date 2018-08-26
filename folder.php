<?php
require_once "php/db_connect.php";
require_once "php/functions.php";
require_once "php/createtable.php";

session_start();

$_user = $_SESSION["username"];

if(!$_user)
{
    header("Location: index.php");
}


//performs the query for if delete button is pressed
if (isset ($_POST['action']))
{   
    $id = $_POST['action'];
    $var = "DELETE FROM FOLDER WHERE FNAME = '$id' AND USERNAME = '$_user'";
    if(!($db->query($var))) 
        {
            echo '<div class="alert alert-danger">Something went wrong: (' . $db->errno . ') ' . $db->error . '</div>' . PHP_EOL;
            exit(); // Prevents the rest of the file from running
        };
}

//adds folder to folder
if (isset($_POST['addf']))
{   
    $id = $_POST['addf'];
    
    $selectStmt = "SELECT * FROM FOLDER WHERE username='".strtolower($_user)."' AND FNAME = '".$id."'";
    $result = $db->query($selectStmt);
    if($result->num_rows > 0)
    {
        echo '<div class="alert alert-danger">Sorry You already have a folder with this name</div>';
    }
    else
    {
    $var = "INSERT INTO FOLDER(USERNAME, FNAME) VALUES ('".strtolower($_user)."','".$id."')";
    if(!($db->query($var))) 
        {
            echo '<div class="alert alert-danger">Something went wrong: (' . $db->errno . ') ' . $db->error . '</div>' . PHP_EOL;
            exit(); // Prevents the rest of the file from running
        };
    echo "<script type='text/javascript'>alert('message');</script>";
    header("Location: folder.php");
    }
    
}




?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	
	<title>Image uploader</title>
	
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="css/bootstrap-theme.min.css">
    
    <link rel="stylesheet" href="css/styles.css">
	
	<link href="http://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    
    <script src="js/bootstrap.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script src="functions.js"></script>
    

    
    

</head>
<body onload="initialize();">
    

    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">
            Post-It
          </a>
        </div>
        <ul class="nav navbar-nav">
          <li><a href="postcard.php">Feed</a></li>
            <li class="active"><a href="folder.php">Folders</a></li>
          <li><a href="upload.php">Post Something!</a></li>

        </ul>
        <p class="navbar-text navbar-right">Signed in as <?php echo $_user ?></p>
        <a href="signout.php"><button type="button" class="btn btn-default navbar-btn navbar-right">Sign out</button></a>
      </div>
    </nav>
    <div class="container padd">
        
		<div class="row">
			<div id="formParent" class="col-md-6 col-md-offset-3" style = "background-color:#eeeee; border-radius: 8px;">
            <div class="input-group ccc">
            <span class="input-group-addon" style ="border-width: 0px;"><span class="fa fa-folder fa-2x"></span></span>
                <div class="form-group"><h1>Edit Folders</h1></div>
            </div>
            <?php
                echo folder($db, $_user)
            ?>
                
                <script>
                    $( ".minuss" ).click(function() {
                        var vall = $(this).attr("id");

                        $.ajax({
                            type: "POST",
                            url: window.location.href,
                            data: {action: vall},
                            cashe: false
                        });
                        $( this ).parent().parent().slideUp();
                    });
                </script>
                <div class="fder1" id= "added"><div class="txt11" id = "add1"> Add Folder<i id = "add" class="fa fa-plus fa-lg pluss"></i></div></div>
                
                <script>
                    $( "#add1" ).click(function() {
                        $( this ).replaceWith( '<form method="POST" action="folder.php"><input type = "text" class="addtxt" name="addf" id="addf" autofocus="autofocus"><input id="addsub" type="submit" value="submit"></form>' );
                
                    });
                </script>
    

			</div>
		</div>
    </div>
    
    
    
    
    
	<!-- JavaScript placed at bottom for faster page loadtimes. -->
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	
	<!-- Latest compiled and minified JavaScript -->
    

</body>
</html>
<?php $db->close(); ?>