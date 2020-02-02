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
    // GET
    //======================================================================

    /**
     * GET /beacons/group/{id}/location/all
     */
    public function get_index($args)
    {
        $result = $this->beacon_location_interface->getAllFromGroup($args);
        if ($result) {
            return $this->response(true, $result, 200);
        } else {
            return $this->response(false, "Invalid Group ID", 404);
        }
    }

    /**
     * GET /beacons/group/{id}/location/{beacon_id}
     */
    public function get_byId($args)
    {
        if (isset($args['id']) && isset($args['beacon_id'])) {
            $result = $this->beacon_location_interface->getByIdFromGroup($args['beacon_id'], $args['id']);
            if ($result) {
                return $this->response(true, $result, 200);
            } else {
                return $this->response(false, "Beacon Location not found.", 404);
            }
        } else {
            return $this->response(false, "Please provide an ID.", 400);
        }
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

    //======================================================================
    // PUT
    //======================================================================

    /**
     * PUT /beacons/{id}/location
     */
    public function put_id($args, $data)
    {
        if (!isset($args['id'])) {
            return $this->response(false, "Error: Invalid ID. Beacon Group not updated", 400);
        }

        $data['beacons_id'] = $args['id'];
        $result = $this->beacon_location_interface->update($data);

        if (!$result) {
            return $this->response(false, "Error: Beacon Group not updated", 400);
        }

        return $this->response(true, "Updated Beacon Location", 200);
    }

    //======================================================================
    // DELETE
    //======================================================================

    /**
     * DELETE /beacons/group/{id}
     */
    public function delete_id($args, $data)
    {
        $result = $this->beacon_location_interface->delete($args, $data);

        if (!$result) {
            return $this->response(false, "Error: Beacon not deleted", 400);
        }

        return $this->response(true, "Beacon Deleted", 200);
    }
}
