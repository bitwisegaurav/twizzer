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
            <a href="home.php" class="logo">
                <img src="https://static.vecteezy.com/system/resources/previews/003/607/702/original/bubble-chat-icon-inside-orange-circle-with-long-shadow-using-for-presentation-website-and-application-vector.jpg" alt="Logo 1">
            </a>
            <h2>Blogesation</h2>
            <button class="createBtn" style="padding: 10px 20px;border-radius: 10px;outline: none;border: none;background: white;">
                <a href="createBlog.php" style="color: #ff7f00; text-decoration: none; cursor: pointer;">Create Blog</a>
            </button>
            <a href="#" class="people">
                <img src="https://w7.pngwing.com/pngs/527/663/png-transparent-logo-person-user-person-icon-rectangle-photography-computer-wallpaper.png" alt="Logo 2">
            </a>
        </header>
        
        <main>
            <div id="profile">
                <div class="left">

                    <div class="profile-img">
                        <img src="https://w7.pngwing.com/pngs/527/663/png-transparent-logo-person-user-person-icon-rectangle-photography-computer-wallpaper.png" alt="Profile picture">
                    </div>
                    <p>@bitwisegaurav</p>
                </div>
                <div class="about">
                    <p>Name : Gaurav Mishra</p>
                    <p>Followers : 344</p>
                    <p>About : 7.3.0</p>
                    <p>Following : 458</p>
                    <p id="blogsCount">Blogs : 0</p>
                    <p>Joined on : 1 day ago</p>
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
