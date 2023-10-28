<?php
    $servername = "localhost";
    $serverusername = "root";
    $serverpassword = "";
    $dbname = "blogesation";
    $conn = mysqli_connect($servername, $serverusername, $serverpassword);

    if (mysqli_connect_errno()) {
        die('Error: '. mysqli_connect_error());
    }

    $dbsql = "CREATE DATABASE IF NOT EXISTS {$dbname}";

    if(!mysqli_query($conn, $dbsql)){
        die('Database is not created. Error'. mysqli_connect_error());
    }

    mysqli_select_db($conn, $dbname); 

    $userstableName = 'users';
    $userstablesql = "CREATE TABLE IF NOT EXISTS {$userstableName} (id INT AUTO_INCREMENT PRIMARY KEY, username varchar(40), name varchar(40), about varchar(60), email varchar(40), password varchar(255), blogs int, followers int, following int, dob date, joined date)";
    
    if(!mysqli_query($conn, $userstablesql)){
        die('Table is not created. Error'. mysqli_connect_error());
    }

    $blogstableName = 'blogs';
    $blogstablesql = "CREATE TABLE IF NOT EXISTS {$blogstableName} (time int, username varchar(40),title varchar(225), description text, likes int, dislikes int)";
    
    if(!mysqli_query($conn, $blogstablesql)){
        die('Table is not created. Error'. mysqli_connect_error());
    }
    
    return $conn;
?>