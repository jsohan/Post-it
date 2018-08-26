<?php


$selectStmted = "SELECT * FROM USERS";
//creates table  USERS if not there
if(!($db->query($selectStmted))) {
    
    $createStmt = "CREATE TABLE USERS (
      username varchar(50) NOT NULL,
      password varchar(50) NOT NULL)";
        
    if(!($db->query($createStmt))) 
        {
            echo '<div class="alert alert-danger">Table creation failed: (' . $db->errno . ') ' . $db->error . '</div>' . PHP_EOL;
            exit(); // Prevents the rest of the file from running
        }
}



$selectStmted2 = "SELECT * FROM WALL";
//cerates Table WALL if it is not there
 if(!($db->query($selectStmted2))) {
     
    $createStmt = "CREATE TABLE WALL(
    USERNAME VARCHAR(30) NOT NULL,
    TEXT VARCHAR(140) NOT NULL,
    TITLE VARCHAR(140) NOT NULL,
    IMAGE_NAME VARCHAR(50) NOT NULL,
    TIME_STAMP VARCHAR(50) NOT NULL,
    FNAME VARCHAR(50) NOT NULL,
    FOREIGN KEY (USERNAME) REFERENCES USERS(USERNAME),
    FOREIGN KEY (FNAME) REFERENCES FOLDER(FNAME),
    PRIMARY KEY (USERNAME, TIME_STAMP))";
        
    if(!($db->query($createStmt))) 
        {
            echo '<div class="alert alert-danger">Table creation failed: (' . $db->errno . ') ' . $db->error . '</div>' . PHP_EOL;
            exit(); // Prevents the rest of the file from running
        }
}


$selectStmted3 = "SELECT * FROM FOLDER";
// creates table FOLDER if not there
if(!($db->query($selectStmted3))) {
    $createfld = "CREATE TABLE FOLDER(
    USERNAME VARCHAR(30) NOT NULL,
    FNAME VARCHAR(30) NOT NULL,
    FOREIGN KEY (USERNAME) REFERENCES USERS(USERNAME),
    FOREIGN KEY (FNAME) REFERENCES WALL(FNAME)
    PRIMARY KEY (USERNAME,FNAME))";
    if(!($db->query($createfld))) 
        {
            echo '<div class="alert alert-danger">Table creation failed: (' . $db->errno . ') ' . $db->error . '</div>' . PHP_EOL;
            exit(); // Prevents the rest of the file from running
        }
    
}

?>