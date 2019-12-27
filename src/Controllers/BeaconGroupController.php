<?php

namespace pats\Controllers;

use pats\Controllers\RESTController;
use pats\Interfaces\BeaconGroupInterface;

class BeaconGroupController extends RESTController
{
    private $beacon_group_interface;

    /**
     * Constructs the controller.
     */
    public function __construct()
    {
        $this->beacon_group_interface = new BeaconGroupInterface();
    }

    //======================================================================
    // GET
    //======================================================================

    /**
     * GET /beacons/group/all
     */
    public function get_index()
    {
        $result = $this->beacon_group_interface->getAll();
        return $this->response(true, $result, 200);
    }

    /**
     * GET /beacons/group/{id}
     */
    public function get_byId($data)
    {
        if (isset($data['id'])) {
            $result = $this->beacon_group_interface->getById($data['id']);
            if ($result) {
                return $this->response(true, $result, 200);
            } else {
                return $this->response(false, "Beacon not found.", 404);
            }
        } else {
            return $this->response(false, "Please provide an ID.", 400);
        }
    }

    //======================================================================
    // POST
    //======================================================================

    /**
     * POST /beacons/group
     */
    public function post_index($data)
    {
        $result = $this->beacon_group_interface->create($data);

        if (!$result) {
            return $this->response(false, "Error: Beacon group entry not created", 400);
        }

        return $this->response(true, ['id' => $result], 201);
    }

    //======================================================================
    // PUT
    //======================================================================

    /**
     * PUT /beacons/group/{id}
     */
    public function put_id($args, $data)
    {
        if (!isset($args['id'])) {
            return $this->response(false, "Error: Invalid ID. Beacon Group not updated", 400);
        }

        $data['id'] = $args['id'];
        $result = $this->beacon_group_interface->update($data);

        if (!$result) {
            return $this->response(false, "Error: Beacon Group not updated", 400);
        }

        return $this->response(true, ['id' => $result], 200);
    }

    //======================================================================
    // DELETE
    //======================================================================

    /**
     * DELETE /beacons/group/{id}
     */
    public function delete_id($data)
    {
        $result = $this->beacon_group_interface->delete($data);

        if (!$result) {
            return $this->response(false, "Error: Beacon not deleted", 400);
        }

        return $this->response(true, ['id' => $result], 200);
    }
}
