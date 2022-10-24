<?php
require_once ("Db.php");

class Auth extends Db
{
    private PDO $dbInstance;

    private int $id = 0;
    private string $name = "";
    private string $login = "";
    private string $password = "";
    private int $role = 0;
    private bool $isValidUser = false;


    public function __construct($login, $password)
    {
        parent::__construct();
        $this->dbInstance = parent::getInstance();
        $this->login = $login;
        $this->password = $password;
        $this->setFields();
    }

    protected function setFields() {
        $sql = "select * from system_users where login = '$this->login' and password = '$this->password'";
        $query = $this->dbInstance->prepare($sql);
        $query->execute();
            while ($row = $query->fetch((PDO::FETCH_ASSOC))) {
                $this->id = $row["id"];
                $this->name = $row["name"];
                $this->role = $row["role"];
                $this->isValidUser = true;
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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @return int
     */
    public function getRole(): int
    {
        return $this->role;
    }

    /**
     * @return bool
     */
    public function isValidUser(): bool
    {
        return $this->isValidUser;
    }

    public function getHash():string {
        if ($this->isValidUser()) {
            return sha1($this->id.$this->login.$this->role);
        } else return "";
    }

}