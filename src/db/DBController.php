<?php

namespace pats\DB;

use PDO;

class DBController
{
    private $db_server;
    private $db_user;
    private $db_password;
    private $db_name;
    private $connection;

    public function __construct()
    {
        // $this->decodeCredentials();
        $this->db_server = "localhost";
        $this->db_user = "brandon";
        $this->db_password = "brandon";
        $this->db_name = "pats";
    }

    public function connect()
    {
        try {
            $this->connection = new PDO("mysql:host=$this->db_server;dbname=$this->db_name", $this->db_user, $this->db_password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $msg = "Database connection failed";
            exit($msg);
        }
    }

    public function disconnect()
    {
        $this->connection = null;
    }

    public function execQuery($sql, $args = null)
    {        
        if (empty($args)) {
            $result = $this->connection->execute();
        } else {
            $stmt = $this->connection->prepare($sql);
            $result = $stmt->execute($args);
        }

        return $result;
    }

    public function readQuery($sql, $args = null)
    {
        if (empty($args)) {
            $results = $this->connection->query($sql)->fetchAll();
            return $results;
        } else {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($args);
            return $stmt->fetchAll();
        }
    }

    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }

    private function decodeCredentials()
    {
        $db_info_json = file_get_contents("database_info.json");
        $db_info = json_decode($db_info_json, true);

        $this->db_server = $db_info['db_server'];
        $this->db_user = $db_info['db_server'];
        $this->db_password = $db_info['db_password'];
        $this->db_name = $db_info['db_name'];
    }
}
