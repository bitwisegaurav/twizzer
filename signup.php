<?php
    session_start();

    $msg = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if (isset($_POST['username']) && isset($_POST['name']) && isset($_POST['about']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['dob'])){
            $username = $_POST['username'];
            $name = $_POST['name'];
            $about = $_POST['about'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $dob = $_POST['dob'];

            $conn = require('first.php');

            $checkUsernameQuery = "SELECT COUNT(*) FROM users WHERE username = '$username'";
            $checkUsernameResult = mysqli_query($conn,$checkUsernameQuery);
            $checkUsernameRow = mysqli_fetch_assoc($checkUsernameResult);
            if($checkUsernameRow > 0){
                $msg = "Username already exists";
            } else {
                $insertUserQuery = "INSERT INTO users (username, name, about, email, password, blogs, followers, following, dob, joined) VALUES ('$username','$name','$about','$email','$password','0','0','0','$dob', '". date('Y-m-d') ."')";
                
                if(mysqli_query($conn, $insertUserQuery)){
                    $_SESSION["username"] = $username;
                    $_SESSION["password"] = $password;
                    header('location: home.php');
                }
                else {
                    $msg = "Error creating user" . mysqli_error($conn);
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Blogesation</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #ff7f00;
            min-height: 100vh;
            box-sizing: border-box;
        }

        section {
            flex: 1;
            width: 100%;
            max-width: 800px;
            margin: 30px 0;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        /* Form Styles */
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2rem;
            padding: 2rem;
            height: 100%;
        }

        form > h1 {
            margin: 0;
            color: #ff7f00;
            font-size: 2rem;
            text-align: center;
        }

        .input-group {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-evenly;
            gap: 3rem;
            /* flex-direction: column; */
            width: 100%;
        }
        .input-group .inputBox{
            display: grid;
            gap: 1rem;
            width: calc(50% - 2rem);
        }

        .input-group label {
            display: flex;
            width: 100%;
            font-weight: bold;
            color: #4e3b28;
        }
        
        .input-group input {
            display: flex;
            padding: 10px;
            border: 1px solid #ff7f00;
            border-radius: 5px;
            outline: none;
            background-color: #fff;
            transition: border-color 0.3s;
        }

        .input-group input:focus {
            border: 2px solid orangered;
        }

        .input-group input::placeholder {
            color: #999;
        }

        .btns{
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
        }

        button, a {
            display: grid;
            place-items: center;
            padding: 10px 20px;
            background-color: #ff7f00;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            font-size: 1rem;
        }

        button:hover, a:hover {
            background-color: #c86400;
        }

        @media screen and (max-width: 668px) {
            .input-group .inputBox{
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <section>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
            <h1>Sign Up</h1>
            <?php echo $msg; ?>
            <div class="input-group">
                <div class="inputBox">
                    <label for="username">Username :</label>
                    <input type="text" name="username" placeholder="Enter your username" required/>
                </div>
                <div class="inputBox">
                    <label for="name">Name :</label>
                    <input type="text" name="name" placeholder="Enter your name" required/>
                </div>
                <div class="inputBox">
                    <label for="about">About :</label>
                    <input type="text" name="about" placeholder="Tell us about yourself" required/>
                </div>
                <div class="inputBox">
                    <label for="email">Email :</label>
                    <input type="email" name="email" placeholder="Enter your email" required/>
                </div>
                <div class="inputBox">
                    <label for="password">Password :</label>
                    <input type="password" name="password" placeholder="Enter your password" required/>
                </div>
                <div class="inputBox">
                    <label for="dob">DOB :</label>
                    <input type="date" name="dob" placeholder="Enter your date of birth" required/>
                </div>
            </div>
            <div class="btns">
                <button type="submit">Submit</button>
                <a href="login.php">Log In</a>
            </div>
        </form>
    </section>
</body>
</html>