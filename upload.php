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
//gets all the values from the forum and caclls a function with the proper perameters
    if (isset($_POST['title']) && isset($_POST['text']))
    {

        date_default_timezone_set("America/New_York");
        $_time = date("m/d") . "   " .date("g:i a");
        $_title = $_POST['title'];
        $_text = $_POST['text'];
        $_folder = $_POST['folder'];
            
        $file_names=date("mdgis");
        $temp_name=$_FILES["upload"]["tmp_name"];
        $base = basename($_FILES["upload"]["name"]);
        $imageFileType = pathinfo($base,PATHINFO_EXTENSION);
        $imagename=date("mdgis").".".$imageFileType;
        $file_name=$imagename;
        $target="images/".$file_name;
        //$target_path = "BU/";
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["upload"]["tmp_name"]);  
        if($check !== false) {
            chmod("images", 0777);
            if(move_uploaded_file($_FILES['upload']['tmp_name'], $target)) {
                SavePostToDB($db, $_user, $_title, $_text, $_time, $file_name, $_folder);
                header("Location: index2.php");
                
            }
            else {
                echo '<div class="alert alert-danger">image cannot be saved! Most likely need to change permissions on "images" folder</div>';
            }
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
            <li><a href="folder.php">Folders</a></li>
          <li class="active"><a href="upload.php">Post Something!</a></li>
        </ul>
        <p class="navbar-text navbar-right">Signed in as <?php echo $_user ?></p>
        <a href="signout.php"><button type="button" class="btn btn-default navbar-btn navbar-right">Sign out</button></a>
      </div>
    </nav>
	<div class="container padd">    
		<div class="row">
			<div id="formParent" class="col-md-6 col-md-offset-3">
				<form id="form" class="form-horizontal" method="POST" action="upload.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name" class="control-label col-xs-1">Name</label>
                        <div class="col-xs-11">
                            <div class="input-group borderx">
                                
                                <span class="borderz"><?php echo $_user ?><span class="fa fa-user fa-fw"></span></span>
                                
                          <!--      <input type="text" class="form-control" id="name" name="name" 
                            maxlength="20" size="20" value="" required placeholder="Johnny" autofocus> -->
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="folder" class="control-label col-xs-1">Folder</label>
                        <div class="col-xs-11">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="fa fa-folder fa-fw"></span></span>
                                <select name="folder" id="folder" class="form-control">
                                    <!-- interactive drop down -->
                                    <?php
                                        echo dropdown($db, $_user)
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="title" class="control-label col-xs-1">Title</label>
                        <div class="col-xs-11">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="fa fa-header fa-fw"></span></span>
                                <input type="text" class="form-control" id="title" name="title" required=""
                            maxlength="20" size="20" value="" required placeholder="Title" autofocus>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="text" class="control-label col-xs-1">Text</label>
                        <div class="col-xs-11">
                            <textarea class="form-control" id="text" name="text" maxlength="140" placeholder="140 characters" required=""></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="sr-only" for="image">Original Image</label>
                        <img id="image" name="image" src="/" width="100%">
                        <input type="file" id="upload" name="upload" required="" accept="image/*">
                    </div>
                    
                    <!--
                    <div class="form-group">
                        <h3>Filter Photo</h3>
                        <div class="checkbox-inline">
                            <label for="myNostalgia">My Nostalgia</label>
                            <input type="radio" name="filter" id="myNostalgia" value="myNostalgia" onclick="applyMyNostalgiaFilter();">
                        </div>
                        <div class="checkbox-inline">
                            <label for="grayscale">Grayscale</label>
                            <input type="radio" name="filter" id="grayscale" value="grayscale" onclick="applyGrayscaleFilter();">
                        </div>
                        <div class="checkbox-inline">
                            <label for="original">Revert to Original</label>
                            <input type="radio" name="filter" id="lomo" value="lomo" onclick="revertToOriginal();">
                        </div>
                    </div>                
                    -->
        
                    <input type="submit" value="Upload image to wall!" class="btn btn-primary col-md-offset-1">
                    <input type="button" id="resetForm" value="Start over!" class="btn btn-default">
				</form>
			</div>
		</div>
	</div>

	<!-- JavaScript placed at bottom for faster page loadtimes. -->
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	
	<!-- Latest compiled and minified JavaScript -->
	<script src="js/bootstrap.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script src="functions.js"></script>
    

</body>
</html>
<?php $db->close(); ?>