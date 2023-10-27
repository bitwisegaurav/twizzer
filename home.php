<?php
    include('first.php');
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
        <header>
            <a href="index.html" class="logo">
                <img src="https://static.vecteezy.com/system/resources/previews/003/607/702/original/bubble-chat-icon-inside-orange-circle-with-long-shadow-using-for-presentation-website-and-application-vector.jpg" alt="Logo 1">
            </a>
            <h2>Blogesation</h2>
            <a href="profile.html" class="people">
                <img src="https://w7.pngwing.com/pngs/527/663/png-transparent-logo-person-user-person-icon-rectangle-photography-computer-wallpaper.png" alt="Logo 2">
            </a>
        </header>
        <main>
            <?php
                echo $data;
            ?>
        </main>
    </section>

</body>
</html>