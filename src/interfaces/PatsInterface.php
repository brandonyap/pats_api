<?php

namespace pats\Interfaces;

use pats\DB\DBController;

class PatsInterface
{
    public $db;

    public function __construct()
    {
        $this->db = new DBController();
        $this->db->connect();
    }

    public function __destruct()
    {
        $this->db->disconnect();
    }
}
