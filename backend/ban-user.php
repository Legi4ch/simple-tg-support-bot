<?php
session_start();
if (!$_SESSION["auth"]) {
    die();
}
require "../classes/User.php";
require "../config/Config.php";

$banId = intval($_GET["id"]);
if ($banId == 0 ) {
    die();
}

$user = new User($banId);


if ($user->banUser()) {
    echo "Ok";
}


