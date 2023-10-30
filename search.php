<?php
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if($_POST["username"]){
            $searchUsername = $_POST["username"];
            $conn = require('first.php');
            $searchQuery = "SELECT * FROM users";
            if($searchUsername != '.') $searchQuery .= " WHERE username LIKE '%$searchUsername%'";
            $searchQuery .= " ORDER BY username";
            $result = mysqli_query($conn, $searchQuery);
            $data = "";
            while($row = mysqli_fetch_assoc($result)){
                $data .= '
                <div class="user-profile">
                    <img src="https://w7.pngwing.com/pngs/527/663/png-transparent-logo-person-user-person-icon-rectangle-photography-computer-wallpaper.png" alt="User 1" />
                    <h3 style="margin:0;">'. $row["name"] .'</h3>
                    <p>@'. $row["username"] .'</p>
                    <a href="profile.php?username='. $row["username"] .'">View Profile</a>
                </div>
                ';
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="search.css">
    <title>User Search</title>
</head>
<body>
    <section>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
            <a href="home.php" id="explore">Explore</a>
            <a href="profile.php" id="profile">Profile</a>
            <h1>User Search</h1>
            <div class="input-group">
                <div class="inputBox">
                    <label for="username">Username:</label>
                    <input type="text" name="username" placeholder="Enter username" />
                </div>
            </div>
            <div class="btns">
                <button type="submit">Search</button>
            </div>
        </form>
        
        <div class="usersBox">
            <div class="user-profiles">
                <?php echo $data ?>
            </div>
        </div>
    </section>

</body>
</html>