<?php

namespace pats\DB;

// This file will be added to .gitignore after the first push.
class DBCredentials
{
    // Edit these names for your local MySQL connection
    public $db_server = "localhost";
    public $db_user = "brandon";
    public $db_password = "brandon";
    public $db_name = null;

    public function __construct()
    {
        $strJsonFileContents = file_get_contents(__DIR__ . "/db.json");
        $type = json_decode($strJsonFileContents);

        if ($type->type == "test") {
            $this->db_name = "pats_test";
        } else {
            $this->db_name = "pats";
        }
    }
}
