<?php

require_once ("Db.php");

class Chat extends Db
{
    private PDO $dbInstance;

    public function __construct() {
        parent::__construct();
        $this->dbInstance = parent::getInstance();
    }

    public function getAllChats($userId):array {
        $sql = "select row_number() over (order by last_update desc) as ord, * 
                from active_chats where blocked_by in (0, $userId) or blocked_till < ".time();
        $query = $this->dbInstance->prepare($sql);
        $query->execute();
        $return = array();
        while ($row = $query->fetch((PDO::FETCH_ASSOC)))
        {
            array_push($return,$row);
        }
        return $return;
    }


    public function getChat($chatId, $lastId=0):array {
        if ($lastId > 0) {
            $last = " and cm.id > $lastId";
        } else $last = "";
        $sql = "select cm.*, su.name from chat_messages cm left join system_users su on cm.user_id = su.id where cm.chat_id = :chat_id $last order by dt asc";
        $params = array(
                ":chat_id" => $chatId
                );
        $query = $this->dbInstance->prepare($sql);
        $query->execute($params);
        $return = array();
        while ($row = $query->fetch((PDO::FETCH_ASSOC)))
        {
            array_push($return,$row);
        }
        return $return;
    }

    public function getChatLastId($chatId):int {
        $sql = "select max(id) from chat_messages cm where cm.chat_id = :chat_id";
        $params = array(":chat_id" => $chatId);
        $query = $this->dbInstance->prepare($sql);
        $query->execute($params);
        return intval($query->fetchColumn(0));
    }


    public function isChatBlocked($chatId, $userId):bool {
        $sql = "select id from busy_chats where chat_id = :chat_id and user_id != :user_id and blocked_till > :ts";
        $params = array(
            ":chat_id" => $chatId,
            ":user_id" => $userId,
            ":ts" => time()
        );
        $query = $this->dbInstance->prepare($sql);
        $query->execute($params);
        return $query->fetchColumn(0) ? true : false;

    }

    public function setBlock($chatId, $userId):bool {
        $sql = "
                insert into busy_chats (`chat_id`,`user_id`,`blocked_till`) 
                values (:chat_id, :user_id, :ts) on conflict (chat_id) do UPDATE set blocked_till = :ts, user_id = :user_id
                ";
        $params = array(
                ":chat_id" => $chatId,
                ":user_id" => $userId,
                ":ts" => time() + Config::CHAT_BLOCK_TIME_SEC
                );
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

    public function addAnswer($tgId, $userId, $message):bool {
        $sql = "insert into chat_messages (`chat_id`, `user_id`, `message`, `dt`) values (:chat_id, :user_id, :message, :dt)";
        $params = array(
            ':chat_id' => $tgId,
            ':user_id' => $userId,
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

    public function deleteChat($chatId):bool {
        $sql = "delete from chat_messages where chat_id = :chat_id";
        $params = array(":chat_id" => $chatId);
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

    public function clearBusyChats():bool {
        $sql = "delete from busy_chats where blocked_till < ".time();
        $query = $this->dbInstance->prepare($sql);
        try {
            $this->dbInstance->beginTransaction();
            $query->execute();
            $this->dbInstance->commit();
            return True;
        } catch (Exception $e) {
            $this->dbInstance->rollBack();
            echo $e->getMessage();
            return False;
        }
    }

}