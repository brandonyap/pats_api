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
    public function get_index()
    {
        $result = $this->beacon_interface->getAll();
        return $this->response(true, $result, 200);
    }

    /**
     * GET /beacons/{id}
     */
    public function get_byId($data)
    {
        if (isset($data['id'])) {
            $result = $this->beacon_interface->getById($data['id']);
            if ($result) {
                return $this->response(true, $result, 200);
            } else {
                return $this->response(false, "Beacon not found.", 404);
            }
        } else {
            return $this->response(false, "Please provide an ID.", 400);
        }
    }

    /**
     * POST /beacons
     */
    public function post_index($data)
    {
        $result = $this->beacon_interface->create($data);

        if (!$result) {
            return $this->response(false, "Error: Beacon entry not created", 400);
        }

        return $this->response(true, ['id' => $result], 201);
    }
}
