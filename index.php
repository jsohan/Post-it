<?php
require_once "php/db_connect.php";
require_once "php/createtable.php";

//starts sesssion for log in
session_start();
//if logged in push to news feed page
if (isset($_SESSION["username"]))
{
    $_user = $_SESSION["username"];
    header("Location: postcard.php");
}
else{
    session_unset(); 
    session_destroy();
}


//checks if username and pasword entered are part of an account
if (isset($_POST['username']) && isset($_POST['password']))
{
    $usern = $_POST['username'];
    $passw = $_POST['password'];
    
    $selectStmt = "SELECT * FROM USERS WHERE username='$usern' AND password='$passw'";
        
    $result = $db->query($selectStmt);

    
    if (($result->num_rows) >0)
      {
        session_start();
        $lessMessy = strtolower($usern);
    
        $name = ucfirst($lessMessy);
        $_SESSION["username"] = $name;
        header("Location: postcard.php");
      }
    else {
        erro();
    }
    
}



/*----------------------clear table--------------------
$dropStmt = 'DROP TABLE `USERS`;';
if($db->query($dropStmt)) {
    echo '        <div class="alert alert-success">Table drop successful.</div>' . PHP_EOL;
} 
else {
    echo '        <div class="alert alert-danger">Table drop failed: (' . $db->errno . ') ' . $db->error . '</div>' . PHP_EOL;
    exit();
}
*/
?>

<!-- Latest compiled and minified CSS -->

<html>
    <head>
        <link rel="stylesheet" href="css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">

        <link rel="stylesheet" href="css/main.css">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    </head>
    <body>
        <nav class="navbar navbar-default">
          <div class="container-fluid">
            <div class="navbar-header">
              <a class="navbar-brand" href="postcard.php">
                Post-It
              </a>
            </div>
          </div>
        </nav>
            <?php
                function erro()
                {
                    echo "<div class='alert alert-danger' role='alert'>Dang, wrong username or password</div>";
                }
            ?>
        
        
        <div class="wrapper">
            <form class="form-signin" method="post" action="index.php">       
              <h2 class="form-signin-heading">Please login</h2>
              <input type="text" class="form-control" name="username" placeholder="username" required="" autofocus="" />
              <input type="password" class="form-control" name="password" placeholder="Password" required=""/>
                <a href="index2.php" class="linkz">No login? regester here</a>
              <button class="btn btn-lg btn-primary btn-block but" type="submit">Login</button>   
            </form>
        </div>
    </body>
    
    
    
    
    
    
    

    
    
    
</html>