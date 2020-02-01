<?php

namespace pats\Controllers;

use pats\Controllers\RESTController;
use pats\Interfaces\BeaconLocationInterface;

class BeaconLocationController extends RESTController
{
    private $beacon_group_interface;

    /**
     * Constructs the controller.
     */
    public function __construct()
    {
        $this->beacon_location_interface = new BeaconLocationInterface();
    }

    //======================================================================
    // POST
    //======================================================================

    /**
     * POST /beacons/{id}/location
     */
    public function post_index($args, $data)
    {
        if (!isset($args['id'])) {
            return $this->response(false, "Error: Invalid ID. Beacon Location not created", 400);
        }

        $data["beacons_id"] = $args['id'];

        $result = $this->beacon_location_interface->create($data);

        if (!$result) {
            return $this->response(false, "Error: Beacon group entry not created", 400);
        }

        return $this->response(true, ['id' => $result], 201);
    }
}
