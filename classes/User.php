<?php

require_once ("Db.php");

class User extends Db
{
    private PDO $dbInstance;
    private int $id = 0;
    private int $tgId = 0;
    private string $tgName = "";
    private string $tgFirstName = "";
    private int $regDt = 0;
    private bool $isBan = false;
    private bool $isNewUser = true;


    public function __construct($tgId)
    {
        $this->tgId = $tgId;
        parent::__construct();
        $this->dbInstance = parent::getInstance();
        $query = $this->dbInstance->prepare("select * from chat_users where tg_id = $this->tgId limit 1");
        $query->execute();
        while ($row = $query->fetch((PDO::FETCH_ASSOC))) {
            if ($row["id"] > 0) {
                $this->id = $row["id"];
                $this->tgName = $row["tg_name"];
                $this->tgFirstName = $row["tg_firstname"];
                $this->regDt = $row["dt"];
                $this->isBan = $row["is_ban"] == 0 ? false : true;
                $this->isNewUser = false;
            }
        }
    }

    public function banUser():bool {
        $sql = "update chat_users set is_ban = 1 where tg_id = :tg_id";
        $params = array(":tg_id" => $this->getTgId());
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

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getTgId(): int
    {
        return $this->tgId;
    }

    /**
     * @return string
     */
    public function getTgName(): string
    {
        return $this->tgName;
    }

    /**
     * @return string
     */
    public function getTgFirstName(): string
    {
        return $this->tgFirstName;
    }

    /**
     * @return int
     */
    public function getRegDt(): int
    {
        return $this->regDt;
    }

    /**
     * @return string
     */
    public function getRegDtFormatted(): string
    {
        return date(Config::DT_FORMAT, $this->regDt);
    }

    /**
     * @return bool
     */
    public function isBan(): bool
    {
        return $this->isBan;
    }

    /**
     * @return bool
     */
    public function isNewUser(): bool
    {
        return $this->isNewUser;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->tgId . " " . $this->getTgName() . " " . $this->getTgFirstName();
    }


    public function addUser($tgId, $tgName, $tgFirstName)
    {
        $sql = "insert into chat_users (`tg_id`, `tg_name`, `tg_firstname`, `dt`) values (:tg_id, :tg_name, :tg_firstname, :dt)";
        $params = array(
            ':tg_id' => $tgId,
            ':tg_name' => $tgName,
            ':tg_firstname' => $tgFirstName,
            ':dt' => time());
        $query = $this->dbInstance->prepare($sql);
        try {
            $this->dbInstance->beginTransaction();
            $query->execute($params);
            $this->dbInstance->commit();
            error_log("Запись нового пользователя в базу успешна", 0);
            $this->id = $this->dbInstance->lastInsertId();
            return True;
        } catch (Exception $e) {
            $this->dbInstance->rollBack();
            echo $e->getMessage();
            error_log("Запись нового пользователя не удалась", 0);
            return False;
        }
    }
}
/*
    public function checkUserBlocks():bool {
        $sql = "select count(id) from active_blocks bl where bl.user_id = :userId and bl.dt > :ts";
        $params = array(":userId" => $this->id,":ts" => time());
        $query = $this->dbInstance->prepare($sql);
        $query->execute($params);
        return (intval($query->fetchColumn(0)) > 0) ? true : false;
    }

    public function addUserBlocks($time = 300) {
        $sql = "insert into active_blocks (`user_id`, `dt`) values (:userId, :ts)";
        $params = array(":userId" => $this->id,":ts" => time() + $time);
        $query = $this->dbInstance->prepare($sql);
        $query->execute($params);
    }

*/
