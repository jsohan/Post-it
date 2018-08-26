<?php
require_once "php/db_connect.php";
require_once "php/createtable.php";


session_start();

$_user = $_SESSION["username"];

if ($_user)
{
    header("Location: postcard.php");
}
else{
    session_unset(); 
    session_destroy();
}


?>

<html>
    <head>
        <title>Project 4</title>
        <link rel="stylesheet" href="main.css" />

                <!-- Latest compiled and minified CSS -->

        <link rel="stylesheet" href="css/bootstrap.min.css">

            <!-- Optional theme -->
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">

        <link rel="stylesheet" href="css/main.css">
        
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>


        <script>

           function validateForm() { 
                var z = document.forms["reg"]["username1"].value;
                var x = document.forms["reg"]["password1"].value;
                var y = document.forms["reg"]["password2"].value;

               if (x.length < 5)
                   {
                       alert("Passwords must be at least 6 characters long!");
                    return false;
                   }
               
                if (x != y) {
                    alert("Passwords do not match!");
                    return false;
                }
           }


        </script>

    </head>
    <body>
        <nav class="navbar navbar-default navbar-fixed-top">
          <div class="container-fluid">
            <div class="navbar-header">
              <a class="navbar-brand" href="postcard.php">
                Post-It
              </a>
            </div>
            <ul class="nav navbar-nav">
              <li role="presentation" class="active"><a href="index.php">Home</a></li>
            </ul>
          </div>
        </nav>
        <div class="wrapper">
            <form name="reg" class="form-signin" method="post" action="index2.php" onsubmit="return validateForm()">       
              <h2 class="form-signin-heading">Sign up</h2>
                <h6>Passwords must be at least 6 characters long</h6>
              <input type="text" class="form-control" name="username1" placeholder="Enter Username" required="" autofocus="" />
                <?php
                    //gets potential username and password
                    if (isset($_POST['username1']) && isset($_POST['password1']) && isset($_POST['password2']))
                    {
                        $usern = $_POST['username1'];
                        $passw = $_POST['password1'];

                        $insertStmt = "INSERT INTO USERS (username, password)
                                       VALUES ('$usern', '$passw')";

                        //checks to see if taken
                        $selectStmt = "SELECT * FROM USERS WHERE username='$usern'";
                        $result = $db->query($selectStmt);
                        if($result->num_rows > 0)
                        {
                            echo '<div class="alert alert-danger">Sorry this username is taken</div>';
                        }
                        else{
                            //if username is not taken then it adds to the table and directs you back to the log in page
                            if($db->query($insertStmt)) 
                            {
                                //automatically gives the user a public and private folder
                                $pr = "public";
                                $pu = "private";
                                $insertStmt2 = "INSERT INTO FOLDER (USERNAME, FNAME) VALUES ('$usern', '$pr'), ('$usern', '$pu')";
                                if ($db->query($insertStmt2))
                                {
                                    
                                    ?>
                                    <script type="text/javascript">
                                    alert("Your Account Was Created!!");
                                    window.location.href = "index.php";
                                    </script>
                                    <?php
                                    exit;
                                }
                                else{
                                echo '       <div class="alert alert-danger">Value insertion failed: (' . $db->errno . ') ' . $db->error . '</div>' . PHP_EOL;
                                exit();
                            }
                            }
                            else{
                                echo '       <div class="alert alert-danger">Value insertion failed: (' . $db->errno . ') ' . $db->error . '</div>' . PHP_EOL;
                                exit();
                            }
                        }


                    }
                ?>
              <input type="password" class="form-control" name="password1" placeholder="Enter Password" required=""/>
              <input type="password" class="form-control" name="password2" placeholder="Re-Enter Password" required=""/>

              <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>   
            </form>
          </div>

    </body>
</html>