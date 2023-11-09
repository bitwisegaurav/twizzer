<?php
    session_start();

    $otherusername = $_GET["otherusername"];
    $selfusername = $_SESSION["username"];
    if(!$selfusername) header('location: login.php');

    $conn = require('first.php');

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['msg'])){
            $msg = $_POST['msg'];
            $otherusername = $_POST['otherusername'];
            $time = time();
            $msgInsertQuery = "INSERT INTO chats (time, fromuser, touser, message) VALUES ('$time', '$selfusername', '$otherusername', '$msg')";
            $msgInsertResult = mysqli_query($conn, $msgInsertQuery);
            if (!$msgInsertResult) {
                die('Error: ' . mysqli_error($conn));
            }
        } else {
            die('Error: Message not set');
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - Blogesation by BitwiseGaurav</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            display: grid;
            place-items: center;
            height: 100vh;
            background-color: #ff7f00;
        }
        section{
            width: 90%;
            height: 90vh;
            max-width: 1200px;
            padding: 20px;
            border-radius: 5px;
            background-color: #fff;
            display: flex;
            flex-direction: column;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .chat-section {
            flex: 1;
            overflow-y: auto;
        }

        .chat-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .chat-box {
            width: 100%;
        }
        
        .chat-section::-webkit-scrollbar{
            display: none;
        }
        .chat-message {
            display: inline;
        }
        .chat-message span {
            display: block;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            width: max-content;
            max-width: min(80%, 120rem);
        }
        .other span{
            background-color: #007BFF;
        }
        .self span{
            background-color: #0cb900;
            float: right;
        }

        .input-box {
            display: flex;
            height: 2.5rem;
            gap: 10px;
            margin-top: 20px;
        }
        .input-box input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            outline: none;
        }
        .input-box button {
            padding: 10px 20px;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            border: 1px solid #ccc;
            outline: none;
            background-color: #007BFF;
            color: #fff;
            cursor: pointer;
        }

    </style>
</head>
<body>
    <section>
        <div class="chat-section">
            <div class="chat-container">
                <!-- <div class="other chat-box">
                    <div class="chat-message">
                        <span>Hello, welcome to Blogesation by Bitwisegaurav!</span>
                    </div>
                </div>
                <div class="self chat-box">
                    <div class="chat-message">
                        <span>Hello, welcome to Blogesation by Bitwisegaurav!</span>
                    </div>
                </div> -->
            </div>
        </div>
        
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="input-box">
            <input type="text" name="otherusername" id="otherusername" style="display:none;" value="<?= $otherusername ?>">
            <input type="text" name="msg" id="msg" placeholder="Enter your message here">
            <button type="submit" name="send" id="send">➤</button>
        </form>
    </section>
</body>
<script>
    const chatContainer = document.querySelector('.chat-container');
    const chatSection = document.querySelector('.chat-section');
    const otherusername = <?php echo json_encode($otherusername); ?>;

    function getData(){
        fetch(`chatdata.php?otherusername=${otherusername}`).then(res => {
            if(res.ok){
                return res.text();
            } else {
                return 'Error: ' + res.status;
            }
        }).then(data => {
            chatContainer.innerHTML = data;
            chatSection.scrollTop = chatSection.scrollHeight;
        }).catch(err => {
            console.log(err);
        })
    }
    // getData();
    setInterval(getData, 100);
</script>
</html>