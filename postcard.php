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
    $var = "DELETE FROM WALL WHERE IMAGE_NAME = '$id' AND USERNAME = '$_user'";
    if(!($db->query($var))) 
        {
            echo '<div class="alert alert-danger">Something went wrong: (' . $db->errno . ') ' . $db->error . '</div>' . PHP_EOL;
            exit(); // Prevents the rest of the file from running
        };
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
	
	<title>Image sharing wall</title>
	
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="css/bootstrap-theme.min.css">
    
    <link rel="stylesheet" href="css/styles.css">
	
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    
	<link href="http://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
    
    
    
</head>
<body>

    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="postcard.php">
            Post-It
          </a>
        </div>
        <ul class="nav navbar-nav">
          <li class="active"><a href="postcard.php">Feed</a></li>
            <li><a href="folder.php">Folders</a></li>
          <li><a href="upload.php">Post Something!</a></li>
        </ul>
        <p class="navbar-text navbar-right">Signed in as <?php echo $_user ?></p>
        <a href="signout.php"><button type="button" class="btn btn-default navbar-btn navbar-right">Sign out</button></a>


      </div>
    </nav>

    <div class="container">
        <div class="gap">
            <form method="POST" action="postcard.php">
            <div class="input-group">
                <span class="input-group-addon"><span class="fa fa-folder fa-fw"></span></span>
                <select name="fd" id="fd" class="form-control">
                    <!-- interactive drop down -->
                    <?php
                        echo dropdown($db, $_user)
                    ?>
                </select>
            </div>
            <input id="fsel" type="submit">
            </form>
            <h1>News Feed</h1>
            <?php 
            if (isset ($_POST['fd']))
            {
                echo '<h3>'.ucfirst($_POST['fd']).'</h3>';
                
            }
            else 
            {
                echo '<h3> Public</h3>';
            }

            ?>
        </div>
        <div class="row">
            
            
        <?php 
            if (isset ($_POST['fd']))
            {
                $temp = $_POST['fd'];
                echo getPostcards($db, $_user, $temp); 
            }
            else 
            {
                echo getPostcards($db, $_user);
            }

        ?>
            
            <script> 
            //deletes if delete button is clicked
            $(".alala").click(function() {
                var vall = $(this).attr("id");
                $.ajax({
                    type: "POST",
                    url: window.location.href,
                    data: {action: vall},
                    cashe: false
                });
                $( this ).parent().slideUp()
                
                
            });
            
            
            
            </script>
        </div>
    </div>
</body>



<?php $db->close(); ?>