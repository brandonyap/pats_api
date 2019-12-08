<?php

namespace pats\Interfaces;

use pats\Interfaces\PatsInterface;
use pats\Models\BeaconModel;
use pats\Exceptions\PatsException;

class BeaconInterface extends PatsInterface
{
    /**
     * Constructs the class to use PatsInterface.
     */
    public function __construct()
    {
        parent::__construct();
    }

    //======================================================================
    // CREATE METHODS
    //======================================================================
    
    /**
     * Creates a beacon to be put into the beacons table.
     * @param array $data is the data from the parsed body
     * @return int for the last insert ID or false if the query wasn't completed
     */
    public function create($data)
    {
        $sql = "INSERT INTO beacons (bluetooth_address, name, description)
        VALUES (:bluetooth_address, :name, :description)";

        $beacon_model = $this->createModel($data);
        try {
            $beacon_model->validate();
        } catch (PatsException $e) {
            error_log($e);
            return false;
        }

        $args = [
            ':bluetooth_address' => $beacon_model->bluetooth_address,
            ':name' => $beacon_model->name,
            ':description' => $beacon_model->description
        ];

        $result = $this->db->execQuery($sql, $args);

        if ($result) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    //======================================================================
    // READ METHODS
    //======================================================================

    /**
     * Gets all of the current beacons in the database.
     * @return array of beacons.
     */
    public function getAll()
    {
        $sql = "SELECT * FROM beacons";
        $query = $this->db->query($sql);

        $results = [];

        foreach ($query as $beacon) {
            $results[] = $this->createModel($beacon);
        }

        return $results;
    }

    /**
     * Gets the information on the given beacon id.
     * @param int $id is the beacon id.
     * @return BeaconModel or false if not found.
     */
    public function getById($id)
    {
        $sql = "SELECT * FROM beacons WHERE id = :id";
        $args[':id'] = $id;

        $query = $this->db->query($sql, $args);

        if (!$query) {
            return false;
        }

        if (count($query) > 0) {
            return $this->createModel($query[0]);
        } else {
            return false;
        }
    }

    //======================================================================
    // UPDATE METHODS
    //======================================================================

    public function update($data)
    {
        $sql = "UPDATE beacons
            SET bluetooth_address = :bluetooth_address,
                name = :name,
                description = :description
            WHERE id = :id";

        $beacon_model = $this->createModel($data);
        try {
            $beacon_model->validate();
        } catch (PatsException $e) {
            error_log($e);
            return false;
        }

        $args = [
            ':bluetooth_address' => $beacon_model->bluetooth_address,
            ':name' => $beacon_model->name,
            ':description' => $beacon_model->description,
            ':id' => $beacon_model->id
        ];

        $result = $this->db->execQuery($sql, $args);

        if ($result) {
            return $beacon_model->id;
        } else {
            return false;
        }
    }

    //======================================================================
    // DELETE METHODS
    //======================================================================

    public function delete($data)
    {
        $sql = "DELETE FROM beacons WHERE id = :id";

        $args = [':id' => $data['id']];

        $result = $this->db->execQuery($sql, $args);

        if ($result) {
            return $data['id'];
        } else {
            return false;
        }
    }

    //======================================================================
    // PRIVATE METHODS
    //======================================================================

    /**
     * Creates a model for the beacon.
     * @return BeaconModel model
     */
    private function createModel($data)
    {
        $model = new BeaconModel();

        if (isset($data['id'])) {
            $model->id = $data['id'];
        }
        if (isset($data['bluetooth_address'])) {
            $model->bluetooth_address = $data['bluetooth_address'];
        }
        if (isset($data['name'])) {
            $model->name = $data['name'];
        }
        if (isset($data['description'])) {
            $model->description = $data['description'];
        }

        return $model;
    }
}
