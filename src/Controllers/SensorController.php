<?php

namespace pats\Controllers;

use pats\Controllers\RESTController;
use pats\Interfaces\SensorInterface;

class SensorController extends RESTController
{
    private $sensor_interface;

    /**
     * Constructs the controller.
     */
    public function __construct()
    {
        $this->sensor_interface = new SensorInterface();
    }

    //======================================================================
    // GET
    //======================================================================

    /**
     * GET /sensors
     */
    public function get_index($data)
    {
        if (isset($data['active'])) {
            $active = $data['active'] === 'true' ? true : false;
            $result = $this->sensor_interface->getAll($active);
        } else {
            $result = $this->sensor_interface->getAll();
        }
        return $this->response(true, $result, 200);
    }

    /**
     * GET /sensors/{id}
     */
    public function get_byId($data)
    {
        if (isset($data['id'])) {
            $result = $this->sensor_interface->getById($data['id']);
            if ($result) {
                return $this->response(true, $result, 200);
            } else {
                return $this->response(false, "Sensor not found.", 404);
            }
        } else {
            return $this->response(false, "Please provide an ID.", 400);
        }
    }

    //======================================================================
    // POST
    //======================================================================

    /**
     * POST /sensors
     */
    public function post_index($data)
    {
        $result = $this->sensor_interface->create($data);

        if (!$result) {
            return $this->response(false, "Error: Sensor entry not created", 400);
        }

        return $this->response(true, ['id' => $result], 201);
    }

    //======================================================================
    // PUT
    //======================================================================

    /**
     * PUT /sensors/{id}
     */
    public function put_id($args, $data)
    {
        if (!isset($args['id'])) {
            return $this->response(false, "Error: Invalid ID. Sensor not updated", 400);
        }

        $data['id'] = $args['id'];
        $result = $this->sensor_interface->update($data);

        if (!$result) {
            return $this->response(false, "Error: Sensor not updated", 400);
        }

        return $this->response(true, ['id' => $result], 200);
    }

    //======================================================================
    // DELETE
    //======================================================================

    /**
     * DELETE /beacons/{id}
     */
    public function delete_id($data)
    {
        $result = $this->sensor_interface->delete($data);

        if (!$result) {
            return $this->response(false, "Error: Sensor not deleted", 400);
        }

        return $this->response(true, ['id' => $result], 200);
    }
}
