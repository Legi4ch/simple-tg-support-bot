<?php
require "../classes/TgReceiver.php";
require "../classes/TgSender.php";
require "../classes/User.php";
require "../classes/MessageParser.php";
require_once "../config/Config.php";

try {
    $receiver = new TgReceiver(file_get_contents('php://input'));
    $user = new User($receiver->getId());

    if ($receiver->getCommand() == "start") {
        if ($user->isNewUser()) {
            $user->addUser($receiver->getId(), $receiver->getName(), $receiver->getFirstName());
        }
        TgSender::sendTextMessage($receiver->getId(), Config::MSG_HELLO_MSG);
    } else {
        if (!$user->isBan() && !$user->isNewUser()) {
            $parser = new MessageParser($user, $receiver);
            $parser->saveMessage();
        }
    }
} catch (Exception $e) {
    error_log($e->getMessage());
} finally {
    echo "200 OK";
}
