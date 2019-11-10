<?php

namespace pats\Interfaces;

use pats\DB\DBController;

class PatsInterface
{
    public $db;

    /**
     * Enables the db to be used by any classes that inherit PatsInterface.
     */
    public function __construct()
    {
        $this->db = new DBController();
        $this->db->connect();
    }

    /**
     * Disconnects the db when any requests are finished.
     */
    public function __destruct()
    {
        $this->db->disconnect();
    }
}
