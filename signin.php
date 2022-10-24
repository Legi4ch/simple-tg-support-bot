<?php

if (isset($_POST["is_form_sent"]) && intval($_POST["is_form_sent"]) == 1) {
    require "config/Config.php";
    require "classes/Auth.php";
    require "classes/Chat.php";

    $chat = new Chat();
    $chat->clearBusyChats();
    unset($chat);

    $auth = new Auth($_POST["login"], $_POST["password"]);
    if ($auth->isValidUser()) {
        session_start();
        $_SESSION["auth"] = true;
        $_SESSION["user_id"] = $auth->getId();
        $_SESSION["user_name"] = $auth->getName();
        $_SESSION["user_role"] = $auth->getRole();
        header("Location: ".Config::SITE_URL);
        die();
    } else {
        die(Config::AUTH_FAIL_MSG);
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign in</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="./css/materialize.min.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="./css/style.css"  media="screen,projection"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>

<div class="container">
    <form action ="signin.php" method="post">
        <input type="hidden" name="is_form_sent" value="1">
        <div class="row center-align">
            <div class="input-field col s12 m4">
                <i class="material-icons prefix">account_box</i>
                <input id="login" name="login" type="text" placeholder="Login">
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m4">
                <i class="material-icons prefix">vpn_key</i>
                <input id="password" name="password" type="password" placeholder="Password">
            </div>
        </div>
        <div class="row">
            <div class="col s8 m6 center-align">
                <button type="submit" href="#" class="btn-small green darken-3"><i class="material-icons left">save</i>Sign in</button>
            </div>
        </div>
    </form>
</div>

<!--JavaScript at end of body for optimized loading-->
<script type="text/javascript" src="./js/materialize.min.js"></script>
<script src="./js/3.6.0.jquery.min.js"></script>
</body>
</html>
