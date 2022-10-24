<?php
session_start();
if (!$_SESSION["auth"]) {
    die();
}

require "../classes/Chat.php";
require "../config/Config.php";
require "../classes/Utils.php";

$userId = $_SESSION["user_id"];
$chat = new Chat();
$chatList = $chat->getAllChats($userId);

if (sizeof($chatList) < 1) { ?>
    <div class="empty_list_placeholder"><?=Config::EMPTY_CHAT_LIST_MSG;?></div>
<?php
    die();
}

foreach ($chatList as $line) {
?>

    <div class="chat" id="<?=$line["chat_id"];?>">
        <span class = "chat-id"><?=$line["user_name"];?></span>
        <?php if($line["sys_name"] && $line["sys_id"] == $userId) { ?>
            <span class="support-answer"><?=Config::CHAT_OWNER_IS_CURRENT_USER;?></span>
        <?php } elseif ($line["sys_name"] && $line["sys_id"] != $userId) { ?>
            <span class="support-answer"><?=sprintf(Config::CHAT_OWNER_IS_ANOTHER_USER,$line["sys_name"]);?></span>
        <?php } ?>
        <span class="chat-message"><?=Utils::trimStr($line["message"], Config::TRIM_MSG_TO);?></span>
        <span class="chat-time"><?=date(Config::DT_FORMAT,$line["last_update"]);?></span>
    </div>
<?php } ?>
