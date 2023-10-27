<?php
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'blogesation';
    $conn = new mysqli($host, $username, $password);

    if ($conn->connect_error) {
        die('Error: '. $conn->connect_error);
    }

    $dbsql = "CREATE DATABASE IF NOT EXISTS {$dbname}";
    $result = $conn->query($sql);

    mysqli_select_db($conn, $dbname); 

    if(!$result){
        die('Database is not created. Error'. $conn->connect_error);
    }

    $tableName = 'blogs';
    $tablesql = "CREATE TABLE IF NOT EXISTS {$tableName} (time int, username varchar(10), description text, likes int, dislikes int)";
    $result = $conn->query($tablesql);

    if(!$result){
        die('Table is not created. Error'. $conn->connect_error);
    }

    $fetchQuery = "SELECT * FROM {$tableName} ORDER BY time";

    $data = "";

    $result = $conn->query($fetchQuery);
    while ($row = $result->fetch_assoc()) { 
        $data .= '
        <article>
        <img src="https://w7.pngwing.com/pngs/527/663/png-transparent-logo-person-user-person-icon-rectangle-photography-computer-wallpaper.png" alt="Profile">
        <div>
            <p>@'. $row["username"] . '</p>
            <p class="desc">'. $row["description"] .'</p>
        </div>
    </article>
        ';
    }
?>