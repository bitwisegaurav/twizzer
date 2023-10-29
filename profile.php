<?php
    session_start();

    $followBtn = "";
    $selfUsername = $_SESSION["username"];

    if(isset($_REQUEST["username"])){
        $otherusername = $_REQUEST["username"];
    }
    else if(isset($_SESSION["username"]) && isset($_SESSION["password"])){
        $otherusername = $_SESSION["username"];
    } else {
        header('location: login.php');
        exit();
    }

    $conn = require('first.php');

    $fetchQuery = "SELECT * FROM users WHERE username = '$otherusername'";
    
    $result = mysqli_query($conn, $fetchQuery);
    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
        $name = $row["name"];
        $about = $row["about"];
        $email = $row["email"];
        $blogs = $row["blogs"];
        $followers = $row["followers"];
        $following = $row["following"];
        $dob = $row["dob"];
        $joined = $row["joined"];
        $interval = date_diff(date_create($joined), date_create('now'));

        $datejoined = '';
        if ($interval->y != 0) {
            $datejoined .= $interval->format('%y years ');
        }
        else if ($interval->m != 0) {
            $datejoined .= $interval->format('%m months ');
        }
        else if ($interval->d != 0) {
            $datejoined .= $interval->format('%d days ');
        }
        else if ($interval->h != 0) {
            $datejoined .= $interval->format('%h hours ');
        }
        else if ($interval->i != 0) {
            $datejoined .= $interval->format('%i mins ');
        }
        else if ($interval->s != 0) {
            $datejoined .= $interval->format('%s secs ');
        } else {
            $datejoined = '0 secs';
        }

        $datejoined = trim($datejoined);
    }

    $checkFollowQuery = "SELECT * FROM followers WHERE fromuser = '$selfUsername' AND touser = '$otherusername'";

    $checkFollowResult = mysqli_query($conn, $checkFollowQuery);
    $followmsg = "Follow";
    if(mysqli_fetch_assoc($checkFollowResult) > 0)
        $followmsg = "Unfollow";

    $editBtn = '';
    if(!$otherusername || ($otherusername && $otherusername == $selfUsername)){
        $followBtn = 'style="display: none;"';
        $editBtn = '<form method="post" action'. $_SERVER["PHP_SELF"] .' class="btnBox" style="width: 100%; display:flex; justify-content: flex-end; gap: 1rem;">
        
        <input type="number" name="deleteTime" value='. $row["time"].' style="display: none;"/>
        
        <button type="button" name="editbtn" style="padding: 5px 8px; background: orange; color: white; border: none; border-radius: 5px;">Edit</button>
        
        <button type="submit" name="deletebtn" style="padding: 5px 8px; background: orange; color: white; border: none; border-radius: 5px;">Delete</button>
        </form>';
    }
    
    $selfUsername = $_SESSION["username"];
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // header('location:profile.php?username=Ruqayys');


        // if(isset($_POST["deletebtn"])){
        //     $deleteTime = $_POST["deleteTime"];
        //     $deleteQuery = "DELETE FROM blogs WHERE time = '$deleteTime'";
        //     $deleteResult = mysqli_query($conn, $deleteQuery);
        //     if($deleteResult){
        //         header("location: profile.php?username=$otherusername");
        //         exit();
        //     } else {
        //         header("location: profile.php?username=$otherusername&info=".mysqli_error($conn)."");
        //     }
        // }
        if(!$selfUsername){
            header('location: login.php');
        }
        else if(isset($_POST["followButton"])){
            $isFollowed = $_POST["isFollowed"] != "Follow";
            $touser = $_POST["touser"];
            $fromuser = $_POST["fromuser"];
            
            if(!$isFollowed && $fromuser != $touser){
                $followquery = "INSERT INTO followers (time,fromuser, touser) VALUES (UNIX_TIMESTAMP(), '$fromuser', '$touser')";
                $updateFollowers = "UPDATE users SET followers = followers + 1 WHERE username = '$touser'";
                $updateFollowing = "UPDATE users SET following = following + 1 WHERE username = '$fromuser'";
                // header('location:profile.php?username='. $fromuser);
                // exit();
            } else{
                $followquery = "DELETE FROM followers WHERE fromuser =  '$fromuser' AND touser = '$touser'";
                $updateFollowers = "UPDATE users SET followers = followers - 1 WHERE username = '$touser'";
                $updateFollowing = "UPDATE users SET following = following - 1 WHERE username = '$fromuser'";
                // header('location:profile.php?username='. $touser);
                // exit();
            }
            
            $followresult = mysqli_query($conn, $followquery);
            $updateFollowersResult = mysqli_query($conn, $updateFollowers);
            $updateFollowingResult = mysqli_query($conn, $updateFollowing);
            if($followresult && $updateFollowersResult && $updateFollowingResult){
                header("location: profile.php?username=".$touser);
                exit();
            } 
            else {
                header("location: profile.php?username=".$touser."&info=".mysqli_error($conn)."");
            }

            // header('location:profile.php?username=bitwisegaurav');
            // exit();
        }

        // header('location:profile.php?username=Ruqayys');
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
        <?php
            if($_REQUEST["info"]){
                echo $_REQUEST["info"];
            }
        ?>
        
        <main>
            <div id="profileInfo">
                <div class="left">
                    <div class="profile-img">
                        <img src="https://w7.pngwing.com/pngs/527/663/png-transparent-logo-person-user-person-icon-rectangle-photography-computer-wallpaper.png" alt="Profile picture">
                    </div>
                    <p>@<?php echo $otherusername ?></p>
                </div>
                <div class="about">
                    <p>Name : <?php echo $name ?></p>
                    <p><a href="followers.php?page=followers&username=<?php echo $otherusername?>" >Followers : <?php echo $followers ?></a></p>
                    <p>About : <?php echo $about ?></p>
                    <p><a href="followers.php?page=following&username=<?php echo $otherusername?>" >Following : <?php echo $following ?></a></p>
                    <p id="blogsCount">Blogs : <?php echo $blogs ?></p>
                    <p>Joined on : <?php echo $datejoined ?> ago</p>
                </div>
            </div>
            <form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>" <?php echo $followBtn ?> class="followBtn">
                <input type="text" name="isFollowed" value="<?php echo $followmsg ?>" readonly style="display:none;">
                <input type="text" name="touser" value="<?php echo $otherusername ?>" readonly style="display:none;">
                <input type="text" name="fromuser" value="<?php echo $selfUsername ?>" readonly style="display:none;">
                <button type="submit" name="followButton" id="followButton">
                    <?php echo $followmsg; ?>
                </button>
            </form>
        </main>
        <div id="blogs">
            <h2>Blogs</h2>
            <?php 
                $conn = require('first.php');

                $fetchQuery = "SELECT * FROM blogs WHERE username = '$otherusername' ORDER BY time DESC";

                $data = "";

                $result = mysqli_query($conn, $fetchQuery);
                
                while ($row = mysqli_fetch_assoc($result)) {

                    $data .= '
                    <article>
                        <a href="#"><img src="https://w7.pngwing.com/pngs/527/663/png-transparent-logo-person-user-person-icon-rectangle-photography-computer-wallpaper.png" alt="Profile"></a>
                        <div>
                            <p><span style="color: #d26900;">'. $name .'</span> @'. $row["username"] . '</p>
                            <p class="desc">'. $row["description"] .'</p>
                            
                        </div>
                    </article>
                    ';
                }

                if($data == "") $data = '<h1 style="color: #916539; text-align:center; margin-top: 10%;font-size: 200%;">No Blogs available</h1>';

                echo $data;
            ?>
        </div>
    </section>

</body>
<script>
    const blogs = document.querySelectorAll('#blogs article');
    const blogsCount = document.querySelector('#blogsCount');
    blogsCount.innerHTML = `Blogs : ${blogs.length}`;
    window.addEventListener("DOMContentLoaded", ()=>{
        document.getElementById('profile').style.display = "none";
    })
    const followbtn = document.getElementById("followbtn");
</script>
</html>