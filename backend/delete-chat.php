<?php
session_start();
if (!$_SESSION["auth"]) {
    die();
}
require "../classes/Chat.php";
require "../config/Config.php";

$delId = intval($_GET["id"]);
if ($delId == 0 ) {
    die();
}

$chat = new Chat();

if ($chat->deleteChat($delId)) {
    echo "Ok";
}


