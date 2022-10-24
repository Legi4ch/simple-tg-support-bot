<?php
session_start();
if (!$_SESSION["auth"]) {
    die();
}

require "../classes/Chat.php";
require "../config/Config.php";
require "../classes/Utils.php";

$userId = $_SESSION["user_id"];
$chatId = intval($_GET["id"]);
if (isset($_GET["last"])) {
    $lastId = intval($_GET["last"]);
}

$chat = new Chat();
$currentLastId = $chat->getChatLastId($chatId);

if ($chat->isChatBlocked($chatId, $userId)) {
   http_response_code(404);
   echo Config::CHAT_IS_BLOCKED_BY_ANOTHER_USER;
   die();
}


if (isset($_GET["get_last"])) {
    echo $currentLastId;
    die();
}

$chat->setBlock($chatId,$userId);

if ($lastId > 0) {
    $content = $chat->getChat($chatId, $lastId);
} else {
    $content = $chat->getChat($chatId);
}



?>



<?php foreach ($content as $line) { ?>
    <?php if ($line["user_id"] == 0) {?>
        <div class="chat-question">
            <span class="chat-question-line"><?=$line["message"];?></span>
            <span class="chat-time"><?=date(Config::DT_FORMAT,$line["dt"]);?></span>
        </div>
    <?php } else { ?>
        <div class="chat-answer">
            <?php if($line["user_id"] == $userId) { ?>
                <span class="chat-answer-line"><?=$line["message"];?></span>
            <?php } elseif ($line["user_id"] != 0) { ?>
                <span class="chat-answer-line">
                    <span class="other-user-answer"><?=sprintf(Config::CHAT_OWNER_IS_ANOTHER_USER,$line["name"])?></span><?=$line["message"];?>
                </span>
            <?php } ?>
            <span class="chat-time"><?=date(Config::DT_FORMAT,$line["dt"]);?></span>
        </div>
    <?php } ?>
<?php } ?>








