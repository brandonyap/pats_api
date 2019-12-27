<?php

namespace pats\Interfaces;

use pats\Interfaces\PatsInterface;
use pats\Models\BeaconGroupModel;
use pats\Exceptions\PatsException;

class BeaconGroupInterface extends PatsInterface
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
     * Creates a beacon group to be put into the beacons_group table.
     * @param array $data is the data from the parsed body
     * @return int for the last insert ID or false if the query wasn't completed
     */
    public function create($data)
    {
        $sql = "INSERT INTO beacons_group (name, description, active)
        VALUES (:name, :description, :active)";

        $beacon_group_model = $this->createModel($data);
        try {
            $beacon_group_model->validate();
        } catch (PatsException $e) {
            error_log($e);
            return false;
        }

        $args = [
            ':name' => $beacon_group_model->name,
            ':description' => $beacon_group_model->description,
            ':active' => intval($beacon_group_model->active)
        ];

        $result = $this->db->execQuery($sql, $args);

        if ($result) {
            return intval($this->db->lastInsertId());
        } else {
            return false;
        }
    }

    //======================================================================
    // READ METHODS
    //======================================================================

    /**
     * Gets all of the current beacon groups in the database.
     * @return array of BeaconGroupModel's.
     */
    public function getAll()
    {
        $sql = "SELECT * FROM beacons_group";
        $query = $this->db->query($sql);

        $results = [];

        foreach ($query as $beacon_group) {
            $results[] = $this->createModel($beacon_group);
        }

        return $results;
    }

    /**
     * Gets the information on the given beacon id.
     * @param int $id is the beacon id.
     * @return BeaconGroupModel or false if not found.
     */
    public function getById($id)
    {
        $sql = "SELECT * FROM beacons_group WHERE id = :id";
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

    /**
     * Updates a beacon group to be put into the beacons_group table.
     * @param array $data is the data from the parsed body
     * @return int for beacon group ID or false if the query wasn't completed
     */
    public function update($data)
    {
        $sql = "UPDATE beacons_group
                SET name = :name,
                description = :description
                WHERE id = :id";

        $beacon_group_model = $this->createModel($data);
        try {
            $beacon_group_model->validate();
        } catch (PatsException $e) {
            error_log($e);
            return false;
        }

        $args = [
            ':name' => $beacon_group_model->name,
            ':description' => $beacon_group_model->description,
            ':id' => $beacon_group_model->id
        ];

        $result = $this->db->execQuery($sql, $args);

        if ($result) {
            return $beacon_group_model->id;
        } else {
            return false;
        }
    }

    //======================================================================
    // DELETE METHODS
    //======================================================================

    /**
     * Deletes a beacon group in the beacons_group table.
     * @param array $data is the id
     * @return int for beacon ID or false if the query wasn't completed
     */
    public function delete($data)
    {
        $sql = "DELETE FROM beacons_group WHERE id = :id";

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
     * Creates a model for the beacon group.
     * @return BeaconGroupModel model
     */
    private function createModel($data)
    {
        $model = new BeaconGroupModel();

        if (isset($data['id'])) {
            $model->id = intval($data['id']);
        }
        if (isset($data['name'])) {
            $model->name = $data['name'];
        }
        if (isset($data['description'])) {
            $model->description = $data['description'];
        }
        if (isset($data['active'])) {
            $model->active = boolval($data['active']);
        }
        if (isset($data['map'])) {
            $model->map = $data['map'];
        }

        return $model;
    }
}
