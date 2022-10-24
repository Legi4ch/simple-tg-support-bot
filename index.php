<?php
session_start();
require "config/Config.php";
if (!isset($_SESSION["auth"])) {
    header("Location: signin.php");
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Интерфейс оператора</title>
    <script src="./js/setting.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="./css/materialize.min.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="./css/style.css"  media="screen,projection"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>

<div class="container">


    <div class="row">
        <div class="col s4 l4 m4">
            <a class="btn waves-effect waves-light green darken-2" id="pause-btn" onclick="stopTimers();">Пауза</a>
            <a class="btn waves-effect waves-light grey lighten-1" href ="logout.php">Выход</a>
        </div>
        <div class="col s8 l8 m8"><!-- верхнее меню ?--></div>
    </div>

    <div class="row">
        <div class="col s4 l4 m4 scroll-chat-list" id="chatsList">
            <!-- CHATS LIST-->
        </div>
        <div class="col s8 l8 m8 scroll-chat-content" id="chat-content">
            <!-- CHAT CONTENT-->
        </div>
    </div>

    <div class="row">
        <div class="col s4 l4 m4">
           <!-- нижнее меню?-->
        </div>
        <div class="col s8 l8 m8" id="message-div">
                <div class="input-field">
                    <i class="material-icons prefix">textsms</i>
                    <input type="text" id="msg" name="msg">
                    <label for="msg">Ответить пользователю</label>
                </div>
                <button class="btn waves-effect waves-light" id="send-btn" onclick="sendMessage()">Отправить</button>



                      <button class="btn waves-effect waves-light" id="send-btn" onclick="selectFile()">
                        <i class="material-icons">attach_file</i>
                    </button>


                <div class="row right-align">
                    <a class="btn waves-effect waves-light red darken-1" id="ban-btn" onclick="banUser()">Блокировать</a>
                    <a class="btn waves-effect waves-light orange darken-4" id="del-btn" onclick="delChat()">Удалить</a>
                </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="./js/materialize.min.js"></script>
<script src="./js/3.6.0.jquery.min.js"></script>
<script src="./js/chat.js"></script>

</body>
</html>
