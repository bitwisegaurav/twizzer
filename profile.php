<?php
    session_start();

    if(!isset($_SESSION['username']) || !isset($_SESSION['password'])){
        header('location: login.php');
    } else {
        $username = $_SESSION['username'];

        $conn = require('first.php');

        $fetchQuery = "SELECT * FROM users WHERE username = '$username'";

        $result = mysqli_query($conn, $fetchQuery);
        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
            $name = $row['name'];
            $about = $row['about'];
            $email = $row['email'];
            $blogs = $row['blogs'];
            $followers = $row['followers'];
            $following = $row['following'];
            $dob = $row['dob'];
            $joined = $row['joined'];
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

        } else {
            header('location: login.php');
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
        
        <main>
            <div id="profile">
                <div class="left">
                    <div class="profile-img">
                        <img src="https://w7.pngwing.com/pngs/527/663/png-transparent-logo-person-user-person-icon-rectangle-photography-computer-wallpaper.png" alt="Profile picture">
                    </div>
                    <p>@<?php echo $username ?></p>
                </div>
                <div class="about">
                    <p>Name : <?php echo $name ?></p>
                    <p>Followers : <?php echo $followers ?></p>
                    <p>About : <?php echo $about ?></p>
                    <p>Following : <?php echo $following ?></p>
                    <p id="blogsCount">Blogs : <?php echo $blogs ?></p>
                    <p>Joined on : <?php echo $datejoined ?> ago</p>
                </div>
            </div>
        </main>
        <div id="blogs">
            <h2>Blogs</h2>
            <?php 
                $conn = require('first.php');

                $fetchQuery = "SELECT * FROM blogs WHERE username = 'bitwisegaurav' ORDER BY time DESC";

                $data = "";

                $result = mysqli_query($conn, $fetchQuery);
                
                while ($row = mysqli_fetch_assoc($result)) { 
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

                echo $data;
            ?>
        </div>
    </section>

</body>
<script>
    const blogs = document.querySelectorAll('#blogs article');
    const blogsCount = document.querySelector('#blogsCount');
    blogsCount.innerHTML = `Blogs : ${blogs.length}`;
</script>
</html>
