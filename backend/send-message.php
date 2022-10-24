<?php
session_start();
if (!$_SESSION["auth"]) {
    die();
}



require "../classes/Chat.php";
require "../classes/TgSender.php";
require "../classes/Utils.php";
require "../config/Config.php";


$userId = $_SESSION["user_id"];
$chatId = intval($_POST["id"]);
$message = $_POST["message"];
if ($chatId == 0 || strlen($message) < 1) {
    die();
}


$chat = new Chat();

if ($chat->addAnswer($chatId, $userId, $message)) {
        TgSender::sendTextMessage($chatId, $message);
        echo "Ok";
} else {
    echo "";
}











