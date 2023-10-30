<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header('location: login.php');
        exit();
    }
    
    $selfUsername = $_REQUEST["username"];
    $followtxt = $_REQUEST["page"];
    $data = "";

    $conn = require('first.php');
    $isFollowers = $followtxt == "following";

    // check the text in followtxt if followers or following and execute that query
    if($isFollowers){
        $fetchfollowersquery = "SELECT touser FROM followers WHERE fromuser = '$selfUsername' ORDER BY touser";
    } else {
        $fetchfollowersquery = "SELECT fromuser FROM followers WHERE touser = '$selfUsername' ORDER BY fromuser";
    }
    $result = mysqli_query($conn, $fetchfollowersquery);
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            // fetch the names from the users table 
            if($isFollowers) $followersusername = $row["touser"];
            else $followersusername = $row["fromuser"];
            $fetchuserquery = "SELECT name FROM users WHERE username = '$followersusername'";
            $result2 = mysqli_query($conn, $fetchuserquery);
            if(mysqli_num_rows($result2) > 0){
                $row2 = mysqli_fetch_assoc($result2);
                $followersname = $row2["name"];
                $data .= '<div class="user-profile">
                            <img src="https://w7.pngwing.com/pngs/527/663/png-transparent-logo-person-user-person-icon-rectangle-photography-computer-wallpaper.png" alt="User 1" />
                            <h2>'.$followersname.'</h2>
                            <p>@'.$followersusername.'</p>
                            <a href="profile.php?username='.$followersusername.'">View Profile</a>
                        </div>';
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Blogesation</title>
    <style>
        *{
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #ff7f00;
            min-height: 100vh;
            box-sizing: border-box;
        }

        section {
            position: relative;
            flex: 1;
            width: 100%;
            height: calc(100vh - 3rem);
            max-width: 1200px;
            background-color: #fff;
            border-radius: 10px; 
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        /* div Styles */
        .searchBox {
            position: sticky;
            top: 0;
            background-color: inherit;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2rem;
            padding: 2rem 2rem 20px;
        }

        .searchBox > h1{
            margin: 0;
            color: #4e3b28;
        }

        .input-group {
            display: flex;
            align-items: flex-end;
            gap: 2rem;
            width: 100%;
        }

        .input {
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 100%; 
        }

        .input label {
            font-weight: bold;
            color: #4e3b28;
        }

        .input input {
            padding: 10px;
            border: 1px solid #ff7f00;
            border-radius: 5px;
            outline: none;
        }
        
        .input input:focus{
            border: 2px solid #ff7f00; 
        }

        .input input::placeholder {
            color: #999;
        }

        button, a {
            padding: 10px 20px;
            background-color: #ff7f00; 
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover, a:hover {
            background-color: #c86400;
        }

        .user-profiles{
            display: flex;
            /* justify-content: space-around; */
            padding: 1rem 2rem;
            flex-wrap: wrap;
            gap: 2rem;
        }

        .user-profile {
            border: 1px solid #ccc;
            width: 12rem;
            border-radius: 5px;
            padding: 10px;
            gap: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .user-profile h2{
            margin: 0;
        }

        .user-profile p{
            font-size: 95%;
        }

        .user-profile a{
            font-size: 85%;
            padding: 8px 16px;
            text-decoration: none;
        }

        .user-profile img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }

        @media screen and (max-width: 768px) {
            section{
                height: 100%;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <section>
        <div class="searchBox">
            <h1><?php
                    echo ucfirst($followtxt);
            ?></h1>
            <div class="input-group">
                <div class="input">
                    <label for="username">Search by username :</label>
                    <input type="text" name="username" placeholder="Enter username" />
                </div>
                <button>Search</button>
            </div>
        </div>
        <div class="searchResults">
            <div class="usersBox">
                <div class="user-profiles">
                    <?php echo $data ?>
                </div>
            </div>
        </div>
    </section>
</body>
<script>
    const searchBtn = document.querySelector(".input-group button");
    const searchInput = document.querySelector(".input-group .input input");
    const usersBox = document.querySelector(".usersBox");
    const userProfiles = document.querySelectorAll(".user-profile");

    searchBtn.addEventListener("click", searchUsername);
    searchInput.addEventListener("keypress", (e)=>{
        if (e.key === "Enter") {
            e.preventDefault();
            searchUsername();
        }
    });

    function searchUsername() {
        const searchValue = searchInput.value.toLowerCase();
        userProfiles.forEach(userProfile => {
            const txt = userProfile.querySelector("p").textContent;
            const username = txt.split("@")[1].toLowerCase();
            if(username.includes(searchValue)){
                userProfile.style.display = "flex";
            } else {
                userProfile.style.display = "none";
            }
        });
    }
</script>
</html>