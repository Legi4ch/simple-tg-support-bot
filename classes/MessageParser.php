<?php
require_once ("Db.php");
require_once ("Utils.php");

class MessageParser extends Db
{
    private PDO $dbInstance;
    private User $user;
    private TgReceiver $receiver;


    public function __construct(User $user, TgReceiver $receiver) {
        parent::__construct();
        $this->dbInstance = parent::getInstance();
        $this->user = $user;
        $this->receiver = $receiver;
    }

    public function saveMessage() {
        $messageImage = "";
        $caption = "";
        if ($this->receiver->getPhotoId()) {
            $photoPatch = $this->getTgFilePath($this->receiver->getPhotoId());
            $photoFile = $this->savePhoto($photoPatch);
            $messageImage = Utils::getImgTag($photoFile);
        }
        if ($this->receiver->getCaption()) {
            $caption = $this->addCaption($this->receiver->getCaption());
        }
        $message = $messageImage.$caption.strip_tags($this->receiver->getMessage());
        $this->insertMessage($this->user->getTgId(), $message);
    }

    private function insertMessage($tgId, $message) {
        $sql = "insert into chat_messages (`chat_id`, `message`,`dt`) values (:chat_id, :message, :dt)";
        $params = array(
                ':chat_id' => $tgId,
                'message'=> $message,
                ':dt' => time());
        $query = $this->dbInstance->prepare($sql);
        try {
            $this->dbInstance->beginTransaction();
            $query->execute($params);
            $this->dbInstance->commit();
            return True;
        } catch (Exception $e) {
            $this->dbInstance->rollBack();
            echo $e->getMessage();
            return False;
        }
    }

    private function getTgFilePath(string $fileId):string {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => Config::TELEGRAM_FILES_API.$fileId
        ]);
        $result = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($result, true);

        if (array_key_exists("file_path",$data["result"])) {
            return $data["result"]["file_path"];
        } else {
            return "";
        }
    }

    private function savePhoto(string $filePath):string {
        $link = Config::TELEGRAM_FILES_PATH.$filePath;
        $fileName = basename($filePath);
        if (copy($link, Config::IMAGES_DOWNLOAD_PATH.$fileName)) {
            return $fileName;
        } else {
            return "";
        }
    }

    private function addCaption($caption):string {
        return '<span class="caption">'.$caption.'</span>';
    }
}