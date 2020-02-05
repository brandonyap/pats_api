<?php

namespace pats\DB;

use PDO;

class DBController
{
    private $db_server = "pats-mysql";
    private $db_user = "pats";
    private $db_password = "41xgroup69";
    private $db_name = "pats";
    private $connection;

    /**
     * This connects the database using PDO.
     */
    public function connect()
    {
        try {
            $this->connection = new PDO("mysql:host=$this->db_server;dbname=$this->db_name", $this->db_user, $this->db_password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            $msg = "Database connection failed";
            exit($msg);
        }
    }

    /**
     * This disconnects the database.
     */
    public function disconnect()
    {
        $this->connection = null;
    }

    /**
     * This executes any CREATE, UPDATE, or DELETE queries into the database.
     * @param string $sql is the SQL query.
     * @param array $args is an array of args for parameter binding.
     * @return bool the success of the query.
     */
    public function execQuery($sql, $args = null)
    {        
        try {
            if (empty($args)) {
                $result = $this->connection->execute();
            } else {
                $stmt = $this->connection->prepare($sql);
                $result = $stmt->execute($args);
            }
            return $result;
        } catch (\PDOException $e) {
            error_log($e);
            return false;
        }
    }

    /**
     * This executes any READ queries into the database.
     * @param string $sql is the SQL query.
     * @param array $args is an array of args for parameter binding.
     * @return bool the success of the query.
     */
    public function query($sql, $args = null)
    {
        try {
            if (empty($args)) {
                $results = $this->connection->query($sql)->fetchAll();
                return $results;
            } else {
                $stmt = $this->connection->prepare($sql);
                $stmt->execute($args);
                return $stmt->fetchAll();
            }
        } catch (\PDOException $e) {
            error_log($e);
            return false;
        }
    }

    /**
     * This grabs the lastInsertId into the database. Useful for CREATE queries.
     * @return int last insert ID
     */
    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }

    public function beginTransaction()
    {
        $this->connection->beginTransaction();
    }

    public function commit()
    {
        $this->connection->commit();
    }

    public function rollBack()
    {
        $this->connection->rollBack();
    }
}
