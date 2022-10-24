<?php

class Db {

    private string $db_path = Config::DB_PATH;
    private PDO $dbInstance;


    function __construct() {
        try {
            $db = new PDO("sqlite:$this->db_path");
            $this->dbInstance = $db;
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            die();
        }
    }

    protected function getInstance() {
        return $this->dbInstance;
    }
}
