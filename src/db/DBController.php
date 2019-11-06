<?php

namespace pats\DB;

use PDO;
use pats\DB\DBCredentials;

class DBController extends DBCredentials
{
    private $connection;

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
}
