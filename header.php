<?php 
    $header = '<header>
            <a href="home.php" class="logo">
                <img src="https://static.vecteezy.com/system/resources/previews/003/607/702/original/bubble-chat-icon-inside-orange-circle-with-long-shadow-using-for-presentation-website-and-application-vector.jpg" alt="Logo 1">
            </a>
            <h2>Blogesation</h2>
            <div class="btns" style="display: flex; gap: 1rem;">
                    <a href="createBlog.php" style="width: 60px; height:40px; display: grid;place-items: center;border-radius: 10px;outline: none;border: none;background: white;color: #ff7f00; text-decoration: none; cursor: pointer;font-size: x-large;">+</a>

                    <a href="search.php" style="width: 60px; height:40px; display: grid;place-items: center;border-radius: 10px;outline: none;border: none;background: white;color: #ff7f00; text-decoration: none; cursor: pointer;">🔍</a>
            </div>
            <div class="links" style="display: flex; gap: 1rem; align-items: center">
                <a href="profile.php" class="people" id="profile">
                    <img src="https://w7.pngwing.com/pngs/527/663/png-transparent-logo-person-user-person-icon-rectangle-photography-computer-wallpaper.png" alt="Logo 2">
                </a>
                <a href="home.php" id="home" style="width: 80px; height:40px; display: grid;place-items: center;border-radius: 10px;outline: none;border: none;background: white;color: #ff7f00; text-decoration: none; cursor: pointer;">Explore</a>
                <a href="sessiondestroy.php" id="logout" style="width: 80px; height:40px; display: grid;place-items: center;border-radius: 10px;outline: none;border: none;background: white;color: #ff7f00; text-decoration: none; cursor: pointer;">Log Out</a>
            </div>
        </header>';

    return $header;
?>