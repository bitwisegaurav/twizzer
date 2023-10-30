<?php
    session_start();
    if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
        header('location: login.php');
    } else {
        $username = $_SESSION["username"];
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

        <main>
            <?php 
                $conn = require('first.php');

                $fetchQuery = "SELECT * FROM blogs ORDER BY time DESC";

                $data = "";
                $editBtnDiv = "";
                $result = mysqli_query($conn, $fetchQuery);
                
                while ($row = mysqli_fetch_assoc($result)) {
                    $user_name = $row["username"];
                    $likes = $row["likes"] ? $row["likes"] : 0;
                    $dislikes = $row["dislikes"] ? $row["dislikes"] : 0;
                    $nameResult = mysqli_query($conn, "SELECT name FROM users WHERE username='$user_name'");

                    if(mysqli_num_rows($nameResult) > 0) {
                        $name = mysqli_fetch_assoc($nameResult)['name'];
                    } else {
                        $name = "Someone";
                    }
                    if($username == $user_name) $editBtnDiv = '<form method="post" action'. $_SERVER['PHP_SELF'] .' class="btnBox" style="">
                    <input type="number" name="deleteTime" value='. $row["time"].' style="display: none;"/>
                    <button type="button" name="editbtn" style="padding: 5px 8px; font-size: 80%;">Edit</button>
                    <button type="submit" name="deletebtn" style="padding: 5px 8px; font-size: 80%;">Delete</button>
                    </form>';

                    $data .= '
                    <article>
                        <a href="profile.php?username='.$user_name.'"><img src="https://w7.pngwing.com/pngs/527/663/png-transparent-logo-person-user-person-icon-rectangle-photography-computer-wallpaper.png" alt="Profile"></a>
                        <div style="width: 100%">
                            <a href="profile.php?username='.$user_name.'" style="text-decoration: none;margin:0;"><p><span style="color: #d26900;">'. $name .'</span> @'. $row["username"] . '</p></a>
                            <p class="desc">'. $row["description"] .'</p>
                            <div class="bottom" style="border: none;">
                                <form method="post" action="'.$_SERVER['PHP_SELF'].'" class="voteBox">
                                    <input type="text" name="votevalue" value="" class="votevalue" readonly style="display:none;"/>
                                    <input type="number" name="blogtime" value="'.$row["time"].'" readonly style="display:none;"/>
                                    <button type="submit" name="upvote" class="upvote" onclick="handleupvote">⬆</button>
                                    <span class="votes">'. ($likes - $dislikes) .'</span>
                                    <button type="submit" name="downvote" class="downvote" onclick="handledownvote">⬇</button>
                                </form>
                            '. $editBtnDiv .'</div>
                        </div>
                    </article>
                    ';
                    $editBtnDiv = "";
                }

                echo $data;
            ?>
        </main>
    </section>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST["deletebtn"])){
            $deleteTime = $_POST["deleteTime"];
            $deleteQuery = "DELETE FROM blogs WHERE time = '$deleteTime'";
            $deleteResult = mysqli_query($conn, $deleteQuery);
            if($deleteResult){
                header("location: home.php");
                exit();
            } else {
                header("location: home.php?info=".mysqli_error($conn)."");
            }
        }
        else if (isset($_POST["upvote"]) || isset($_POST["downvote"])) {
            $blogtime = $_POST["blogtime"];
            $votevalue = $_POST["votevalue"];
            $value = $votevalue === "upvote" ? 1 : 2;
        
            if ($votevalue === "upvote" && $value === 1) {
                // User wants to upvote
                $updateQuery = "UPDATE blogs SET likes = likes + $value WHERE time = '$blogtime'";
            } elseif ($votevalue === "downvote" && $value === 2) {
                // User wants to downvote
                $updateQuery = "UPDATE blogs SET dislikes = dislikes + $value WHERE time = '$blogtime'";
            }
        
            $updateResult = mysqli_query($conn, $updateQuery);
            if (!$updateResult) {
                header("location: home.php?info=" . mysqli_error($conn));
            }
        }
    }
?>

</body>
<script>
    document.querySelector('#home').style.display = "none";
    document.querySelector('#logout').style.display = "none";

    function handleupvote(e) {
        const voteBox = e.target.closest('.voteBox');
        const votevalue = voteBox.querySelector('.votevalue');
        // Check if the last vote was not an upvote
        if (votevalue.value !== 'upvote') {
            votevalue.value = 'upvote';
        }
        e.disabled = true;
        voteBox.querySelector('.upvote').disabled = true;
    }

    function handledownvote(e) {
        const voteBox = e.target.closest('.voteBox');
        const votevalue = voteBox.querySelector('.votevalue');
        // Check if the last vote was not a downvote
        if (votevalue.value !== 'downvote') {
            votevalue.value = 'downvote';
        }
        e.disabled = true;
        voteBox.querySelector('.downvote').disabled = true;
    }
</script>
</html>

<?php 
require('dataUpdate.php');
updateData();
?>