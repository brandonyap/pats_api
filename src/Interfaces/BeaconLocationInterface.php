<?php

namespace pats\Interfaces;

use pats\Interfaces\PatsInterface;
use pats\Models\BeaconLocationModel;
use pats\Exceptions\PatsException;
use pats\Interfaces\BeaconGroupInterface;
use pats\Interfaces\BeaconInterface;

class BeaconLocationInterface extends PatsInterface
{
    private $beacon_group_interface;
    private $beacon_interface;

    /**
     * Constructs the class to use PatsInterface.
     */
    public function __construct()
    {
        parent::__construct();
        $this->beacon_group_interface = new BeaconGroupInterface();
        $this->beacon_interface = new BeaconInterface();
    }

    //======================================================================
    // CREATE METHODS
    //======================================================================
    
    /**
     * Creates a beacon location to be put into the beacons_group_locations table.
     * @param array $data is the data from the parsed body
     * @return int for the last insert ID or false if the query wasn't completed
     */
    public function create($data)
    {
        $sql = "INSERT INTO beacons_group_locations (group_id, beacons_id, location_x, location_y)
        VALUES (:group_id, :beacons_id, :location_x, :location_y)";

        if (!isset($data['group_id']) || !$this->beacon_group_interface->getById(intval($data['group_id'])) || !$this->beacon_interface->getById(intval($data['beacons_id']))) {
            return false;
        }

        $beacon_group_location_model = $this->createModel($data);
        try {
            $beacon_group_location_model->validate();
        } catch (PatsException $e) {
            error_log($e);
            return false;
        }

        $args = [
            ':group_id' => $beacon_group_location_model->group_id,
            ':beacons_id' => $beacon_group_location_model->beacons_id,
            ':location_x' => $beacon_group_location_model->location_x,
            ':location_y' => $beacon_group_location_model->location_y
        ];

        $result = $this->db->execQuery($sql, $args);

        if ($result) {
            return intval($this->db->lastInsertId());
        } else {
            return false;
        }
    }

    //======================================================================
    // PRIVATE METHODS
    //======================================================================

    /**
     * Creates a model for the beacon location.
     * @return BeaconLocationModel model
     */
    private function createModel($data)
    {
        $model = new BeaconLocationModel();

        if (isset($data['id'])) {
            $model->id = intval($data['id']);
        }
        if (isset($data['group_id'])) {
            $model->group_id = intval($data['group_id']);
        }
        if (isset($data['beacons_id'])) {
            $model->beacons_id = intval($data['beacons_id']);
        }
        if (isset($data['location_x'])) {
            $model->location_x = floatval($data['location_x']);
        }
        if (isset($data['location_y'])) {
            $model->location_y = floatval($data['location_y']);
        }

        return $model;
    }
}
