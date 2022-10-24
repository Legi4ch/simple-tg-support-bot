<?php
session_start();
if (!$_SESSION["auth"]) {
    die();
}

$userId = $_SESSION["user_id"];
$chatId = intval($_POST["id"]);


require "../classes/Chat.php";
require "../classes/TgSender.php";
require "../classes/Utils.php";
require "../config/Config.php";



$photo = Utils::uploadPhoto($_FILES['photo']['tmp_name'], Config::IMAGES_DOWNLOAD_PATH.basename($_FILES['photo']['name']));
if ($photo) {
    TgSender::sendPhotoAsUrl($chatId, $photo);
    $message = Utils::getImgTag($photo);
    $chat = new Chat();
    if ($chat->addAnswer($chatId, $userId, $message)) {
        echo "Ok";
    } else {
        echo "Not ok";
    }

} else echo "Error";




