<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "blogesation";
    $conn = mysqli_connect($servername, $username, $password);

    if (mysqli_connect_errno()) {
        die('Error: '. mysqli_connect_error());
    }

    $dbsql = "CREATE DATABASE IF NOT EXISTS {$dbname}";

    if(!mysqli_query($conn, $dbsql)){
        die('Database is not created. Error'. mysqli_connect_error());
    }

    mysqli_select_db($conn, $dbname); 

    $tableName = 'blogs';
    $tablesql = "CREATE TABLE IF NOT EXISTS {$tableName} (time int, username varchar(10), description text, likes int, dislikes int)";
    
    if(!mysqli_query($conn, $tablesql)){
        die('Table is not created. Error'. mysqli_connect_error());
    }

    
    
    return $conn;
?>