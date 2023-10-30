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
                            <div class="bottom">
                                <div class="voteBox">
                                    <input type="text" value="" readonly style="display:none;"/>
                                    <input type="number" value="'.$row["time"].'" readonly style="display:none;"/>
                                    <button class="upvote">⬆</button>
                                    <span class="votes">0</span>
                                    <button class="downvote">⬇</button>
                                </div>
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
            $deleteQuery = "DELETE FROM blogs WHERE time='$deleteTime'";
            if(mysqli_query($conn, $deleteQuery)){
                header("location: home.php");
            } else{
                header("Location: home.php?info=".mysqli_error($conn)."");
            }
        }
    }
?>

</body>
<script>
    document.querySelector('#home').style.display = "none";
    document.querySelector('#logout').style.display = "none";
</script>
</html>

<?php 
require('dataUpdate.php');
updateData();
?>