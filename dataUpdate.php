<?php
    // all the data of users will be updated here 
    function updateData(){
        $conn = require('first.php');
        
        $fetchusers = "SELECT * FROM users";
        
        $result = mysqli_query($conn, $fetchusers);
        
        while ($row = mysqli_fetch_assoc($result)) {
            $username = $row["username"];
            $fetchblogs = "SELECT COUNT(*) FROM blogs WHERE username = '$username'";
            $fetchfollowers = "SELECT COUNT(*) FROM followers WHERE touser = '$username'";
            $fetchfollowing = "SELECT COUNT(*) FROM followers WHERE fromuser = '$username'";
            $blogsResult = mysqli_query($conn, $fetchblogs);
            $followersResult = mysqli_query($conn, $fetchfollowers);
            $followeingResult = mysqli_query($conn, $fetchfollowing);
            $blogs = mysqli_fetch_assoc($blogsResult)['COUNT(*)'];
            $followers = mysqli_fetch_assoc($followersResult)['COUNT(*)'];
            $following = mysqli_fetch_assoc($followeingResult)['COUNT(*)'];
            $updateQuery = "UPDATE users SET blogs='$blogs', followers='$followers', following='$following' WHERE username='$username'";
            mysqli_query($conn, $updateQuery);
        }
    }
?>