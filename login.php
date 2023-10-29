<?php
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if (isset($_POST['username']) && isset($_POST['password'])){
            $username = $_POST['username'];
            $password = $_POST['password'];

            $conn = require('first.php');

            $checkUserQuery = "SELECT * FROM users WHERE username = '$username'";

            $result = mysqli_query($conn, $checkUserQuery);

            if(mysqli_num_rows($result) > 0){
                $row = mysqli_fetch_assoc($result);
                $passwordHash = $row['password'];

                if($password == $passwordHash){
                    $_SESSION["username"] = $username;
                    $_SESSION["password"] = $password;
                    header('location: home.php');
                } else {
                    header('location: login.php?check=wrong');
                }
            }
            else{
                header('location: signup.php');
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
            padding: 20px;
        }

        section {
            flex: 1;
            width: 100%;
            max-width: 500px;
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

        form > h1{
            margin: 0;
            color: #4e3b28;
        }

        .input-group {
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 100%; 
        }

        .input-group label {
            font-weight: bold;
            color: #4e3b28;
        }

        .input-group input {
            padding: 10px;
            border: 1px solid #ff7f00;
            border-radius: 5px;
            outline: none;
        }
        
        .input-group input:focus{
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
            font-size: 16px;
        }

        button:hover, a:hover {
            background-color: #c86400;
        }
    </style>
</head>
<body>
    <section>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
            <h1>Login</h1>
            <?php
                if($_REQUEST["check"] == "wrong")
                    echo "Password is wrong";
            ?>
            <div class="input-group">
                <label for="username">Username :</label>
                <input type="text" name="username" placeholder="Enter your username" required/>
                <label for="password">Password :</label>
                <input type="password" name="password" placeholder="Enter your password" required/>
            </div>
            <div class="btns">
                <button type="submit">Submit</button>
                <a href="signup.php">Sign Up</a>
            </div>
        </form>
    </section>
</body>
</html>