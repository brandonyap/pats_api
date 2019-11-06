<?php

namespace pats\Controllers;

use pats\Controllers\RESTController;
use pats\Interfaces\BeaconInterface;

class BeaconController extends RESTController
{
    private $beacon_interface;

    public function __construct()
    {
        $this->beacon_interface = new BeaconInterface();
    }

    public function index_get()
    {
        $result = $this->beacon_interface->getAll();
        return $this->response(true, $result, 200);
    }

    public function index_post($data)
    {
        $result = $this->beacon_interface->create($data);

        if (gettype($result) == "array") {
            return $this->response($result[0], $result[1], 400);
        }

        if (!$result) {
            return $this->response(false, "Something went wrong", 400);
        }

        return $this->response(true, ['id' => $result], 201);
    }
}
