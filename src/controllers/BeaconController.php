<?php

namespace pats\Controllers;

use pats\Controllers\RESTController;
use pats\Interfaces\BeaconInterface;

class BeaconController extends RESTController
{
    private $beacon_interface;

    /**
     * Constructs the controller.
     */
    public function __construct()
    {
        $this->beacon_interface = new BeaconInterface();
    }

    /**
     * GET /beacons
     */
    public function index_get()
    {
        $result = $this->beacon_interface->getAll();
        return $this->response(true, $result, 200);
    }

    /**
     * POST /beacons
     */
    public function index_post($data)
    {
        $result = $this->beacon_interface->create($data);

        if (!$result) {
            return $this->response(false, "Error: Beacon entry not created", 400);
        }

        return $this->response(true, ['id' => $result], 201);
    }
}
