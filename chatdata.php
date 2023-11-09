<?php

session_start();
$selfusername = $_SESSION["username"];
if (!$selfusername) header('location: login.php');

$otherusername = $_REQUEST["otherusername"];
if(!$otherusername) header('location: chat.php');

$conn = require('first.php');

$msgFetchQuery = "SELECT * FROM chats WHERE (fromuser = '$selfusername' AND touser = '$otherusername') or (fromuser = '$otherusername' AND touser = '$selfusername')";
$msgFetchResult = mysqli_query($conn, $msgFetchQuery);
$data = '';

if (!$msgFetchResult || mysqli_num_rows($msgFetchResult) == 0) {
    $data = '<h1 style="text-align:center;">There are no chats</h1>';
} else {
    while ($row = mysqli_fetch_assoc($msgFetchResult)) {
        $user = '';
        if ($row['fromuser'] == $selfusername) {
            $user .= 'self';
        } else {
            $user .= 'other';
        }
        $data .= '<div class="' . $user . ' chat-box">
                    <div class="chat-message">
                        <span>' . $row['message'] . '</span>
                    </div>
                </div>';
    }
}
    echo $data;
?>