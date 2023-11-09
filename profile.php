<?php
session_start();

$followBtn = ""; // store the display none property if followbtn should not be visible
$selfUsername = $_SESSION["username"]; // username of the user using website

if (isset($_REQUEST["username"])) {
    $otherusername = $_REQUEST["username"]; // username of the user whose profile is visiting
} else if (isset($_SESSION["username"]) && isset($_SESSION["password"])) {
    $otherusername = $_SESSION["username"]; // username of the user if our user visit their own profile
} else {
    header('location: login.php'); // asks to log in if user is not logged in
    exit();
}

$conn = require('first.php'); // connection to the database

// fetching data for username whose profile is visiting
$fetchQuery = "SELECT * FROM users WHERE username = '$otherusername'";

$result = mysqli_query($conn, $fetchQuery);
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $name = $row["name"];
    $about = $row["about"];
    $email = $row["email"];
    $blogs = $row["blogs"];
    $followers = $row["followers"];
    $following = $row["following"];
    $dob = $row["dob"];
    $joined = $row["joined"];
    // get the joined time
    $interval = date_diff(date_create($joined), date_create('now'));

    $datejoined = '';
    if ($interval->y != 0) {
        $datejoined .= $interval->format('%y years ');
    } else if ($interval->m != 0) {
        $datejoined .= $interval->format('%m months ');
    } else if ($interval->d != 0) {
        $datejoined .= $interval->format('%d days ');
    } else if ($interval->h != 0) {
        $datejoined .= $interval->format('%h hours ');
    } else if ($interval->i != 0) {
        $datejoined .= $interval->format('%i mins ');
    } else if ($interval->s != 0) {
        $datejoined .= $interval->format('%s secs ');
    } else {
        $datejoined = '0 secs';
    }

    $datejoined = trim($datejoined);
}

// check if the user is already following the other user
$checkFollowQuery = "SELECT * FROM followers WHERE fromuser = '$selfUsername' AND touser = '$otherusername'";

$checkFollowResult = mysqli_query($conn, $checkFollowQuery);
$followmsg = "Follow"; // default msg for not following
if (mysqli_fetch_assoc($checkFollowResult) > 0)
    $followmsg = "Unfollow"; // changed if user follows the other user

if ($otherusername === $selfUsername) { // checks if user is visiting his own profile
    $followBtn = 'display: none;';
}

// handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!$selfUsername) { // if user is not logged in
        header('location: login.php');
    } 
    else if (isset($_POST["deletebtn"])) { // if user pressed the delete button on blog
        $deleteTime = $_POST["deleteTime"];
        $deleteQuery = "DELETE FROM blogs WHERE time = '$deleteTime'";
        $deleteResult = mysqli_query($conn, $deleteQuery);
        if ($deleteResult) {
            header("location: profile.php");
            exit();
        } else {
            header("location: profile.php?info=" . mysqli_error($conn) . "");
        }
    }
    else if (isset($_POST["upvote"]) || isset($_POST["downvote"])) { // if user interacte with blog (like or dislike)
        $blogtime = $_POST["blogtime"];
        $votevalue = $_POST["votevalue"];
        // $value = $votevalue === "" ? 1 : 2;
    
        if (isset($_POST["upvote"])) {
            // User wants to upvote
            $updateQuery = "UPDATE blogs SET likes = likes + 1 WHERE time = '$blogtime'";
        } elseif (isset($_POST["downvote"])) {
            // User wants to downvote
            $updateQuery = "UPDATE blogs SET dislikes = dislikes + 1 WHERE time = '$blogtime'";
        }
    
        $updateResult = mysqli_query($conn, $updateQuery);
        if (!$updateResult) {
            header("location: profile.php?info=" . mysqli_error($conn));
        }
    }
    else if (isset($_POST["followButton"])) { // for the follow button
        $isFollowed = $_POST["isFollowed"] != "Follow";
        $touser = $_POST["touser"];
        $fromuser = $_POST["fromuser"];

        if (!$isFollowed && $fromuser != $touser) {  // not a follower and not view own profile
            $followquery = "INSERT INTO followers (time,fromuser, touser) VALUES (UNIX_TIMESTAMP(), '$fromuser', '$touser')";
            $updateFollowers = "UPDATE users SET followers = followers + 1 WHERE username = '$touser'";
            $updateFollowing = "UPDATE users SET following = following + 1 WHERE username = '$fromuser'";
        } else { // user is a follower
            $followquery = "DELETE FROM followers WHERE fromuser =  '$fromuser' AND touser = '$touser'";
            $updateFollowers = "UPDATE users SET followers = followers - 1 WHERE username = '$touser'";
            $updateFollowing = "UPDATE users SET following = following - 1 WHERE username = '$fromuser'";
        }

        $followresult = mysqli_query($conn, $followquery);
        $updateFollowersResult = mysqli_query($conn, $updateFollowers);
        $updateFollowingResult = mysqli_query($conn, $updateFollowing);
        if ($followresult && $updateFollowersResult && $updateFollowingResult) {
            header("location: profile.php?username=" . $touser);
            exit();
        } else {
            header("location: profile.php?username=" . $touser . "&info=" . mysqli_error($conn) . "");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Blogesation</title>
</head>
<body>
    <section>
        <?php echo require('header.php') ?>
        <!-- Error msg -->
        <?php
        if ($_REQUEST["info"]) {
            echo $_REQUEST["info"];
        }
        ?>

        <main>
            <div id="profileInfo">
                <div class="left">
                    <div class="profile-img">
                        <img src="https://w7.pngwing.com/pngs/527/663/png-transparent-logo-person-user-person-icon-rectangle-photography-computer-wallpaper.png"
                            alt="Profile picture">
                    </div>
                    <p>@<?php echo $otherusername ?></p>
                </div>
                <div class="about">
                    <p>Name : <?php echo $name ?></p>
                    <p><a href="followers.php?page=followers&username=<?php echo $otherusername ?>">Followers : <?php echo $followers ?></a></p>
                    <p>About : <?php echo $about ?></p>
                    <p><a href="followers.php?page=following&username=<?php echo $otherusername ?>">Following : <?php echo $following ?></a></p>
                    <p id="blogsCount">Blogs :<?php echo $blogs ?></p>
                    <p>Joined on : <?php echo $datejoined ?> ago</p>
                </div>
            </div>
            <!-- Follow and chat button -->
            <div id="bottomprofile" style="display: flex;align-items: center;width: 100%;gap: 1rem;">
                <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>" style="display: flex;align-items: center; padding:0; <?php echo $followBtn ?>" class="followBtn">
                    <input type="text" name="isFollowed" value="<?php echo $followmsg ?>" readonly style="display:none;">
                    <input type="text" name="touser" value="<?php echo $otherusername ?>" readonly style="display:none;">
                    <input type="text" name="fromuser" value="<?php echo $selfUsername ?>" readonly style="display:none;">
                    <button type="submit" name="followButton" id="followButton">
                        <?php echo $followmsg; ?>
                    </button>
                </form>
                <button style="<?php echo $followBtn ?>"><a href="chat.php?otherusername=<?=$otherusername?>" style="color:white;">Chat</a></button>
            </div>
        </main>
        <div id="blogs">
            <h2>Blogs</h2>
            <?php
            $conn = require('first.php');

            // Fetching user blogs
            $fetchQuery = "SELECT * FROM blogs WHERE username = '$otherusername' ORDER BY time DESC";

            $data = "";
            $editBtn = ''; // no edit and delete will be shown by default
            
            $result = mysqli_query($conn, $fetchQuery);

            // fetching data row by row from result
            while ($row = mysqli_fetch_assoc($result)) {
                $likes = $row["likes"] ? $row["likes"] : 0;
                $dislikes = $row["dislikes"] ? $row["dislikes"] : 0;

                if ($otherusername === $selfUsername) { // checks if user is visiting his own profile
                    $editBtn = '<form method="post" action' . $_SERVER["PHP_SELF"] . ' class="btnBox" style="width: 100%; display:flex; justify-content: flex-end; gap: 1rem;">
                        
                    <input type="number" name="deleteTime" value=' . $row["time"] . ' style="display: none;"/>
                    
                    <button type="button" name="editbtn" style="padding: 5px 8px; font-size: 80%;">Edit</button>
                    <button type="submit" name="deletebtn" style="padding: 5px 8px; font-size: 80%;">Delete</button>
                    </form>';
                }

                $data .= '
                    <article>
                        <a href="#"><img src="https://w7.pngwing.com/pngs/527/663/png-transparent-logo-person-user-person-icon-rectangle-photography-computer-wallpaper.png" alt="Profile"></a>
                        <div style="width: 100%;">
                            <p><span style="color: #d26900;">' . $name . '</span> @' . $row["username"] . '</p>
                            <p class="desc">' . $row["description"] . '</p>
                            <div class="bottom">
                                 <form method="post" action="'.$_SERVER['PHP_SELF'].'" class="voteBox">
                                    <input type="text" name="votevalue" value="" class="votevalue" readonly style="display:none;"/>
                                    <input type="number" name="blogtime" value="'.$row["time"].'" readonly style="display:none;"/>
                                    <button type="submit" name="upvote" class="upvote" onclick="handleupvote">⬆</button>
                                    <span class="votes">'. ($likes - $dislikes) .'</span>
                                    <button type="submit" name="downvote" class="downvote" onclick="handledownvote">⬇</button>
                                </form>
                            ' . $editBtn . '</div>
                        </div>
                    </article>
                    ';
            }

            if ($data == "")
                $data = '<h1 style="color: #916539; text-align:center; margin-top: 10%;font-size: 200%;">No Blogs available</h1>';

            echo $data;
            ?>
        </div>
    </section>

</body>
<script>
    const blogs = document.querySelectorAll('#blogs article');
    const blogsCount = document.querySelector('#blogsCount');
    blogsCount.innerHTML = `Blogs : ${blogs.length}`;
    window.addEventListener("DOMContentLoaded", () => {
        document.getElementById('profile').style.display = "none";
    })
    const followbtn = document.getElementById("followbtn");
</script>
</html>