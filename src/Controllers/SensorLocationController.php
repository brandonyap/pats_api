<?php

namespace pats\Controllers;

use pats\Controllers\RESTController;
use pats\Interfaces\SensorLocationInterface;

class SensorLocationController extends RESTController
{
    private $sensor_location_interface;

    /**
     * Constructs the controller.
     */
    public function __construct()
    {
        $this->sensor_location_interface = new SensorLocationInterface();
    }

    //======================================================================
    // POST
    //======================================================================

    /**
     * POST /sensors
     */
    public function post_index($data)
    {
        $result = $this->sensor_location_interface->create($data);

        if (!$result) {
            return $this->response(false, "Error: Sensor entry not created", 400);
        }

        return $this->response(true, "Sensor Location Stored", 201);
    }

}