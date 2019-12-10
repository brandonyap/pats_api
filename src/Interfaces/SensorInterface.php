<?php

namespace pats\Interfaces;

use pats\Interfaces\PatsInterface;
use pats\Models\SensorModel;
use pats\Exceptions\PatsException;

class SensorInterface extends PatsInterface
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
     * Creates a sensor to be put into the sensors table.
     * @param array $data is the data from the parsed body
     * @return int for the last insert ID or false if the query wasn't completed
     */
    public function create($data)
    {
        $sql = "INSERT INTO sensors (bluetooth_address, name, description, active)
        VALUES (:bluetooth_address, :name, :description, :active)";

        $sensor_model = $this->createModel($data);
        try {
            $sensor_model->validate();
        } catch (PatsException $e) {
            error_log($e);
            return false;
        }

        $args = [
            ':bluetooth_address' => $sensor_model->bluetooth_address,
            ':name' => $sensor_model->name,
            ':description' => $sensor_model->description,
            ':active' => intval($sensor_model->active)
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
     * Gets all of the current sensors in the database.
     * @param bool $active adds a WHERE clause if the sensor is active or inactive.
     * @return array of SensorModels's.
     */
    public function getAll($active = null)
    {
        $sql = "SELECT * FROM sensors";

        if (!is_null($active) && is_bool($active)) {
            $sql .= " WHERE active = :active";
            $args = [];
            $args[':active'] = $active;
            $query = $this->db->query($sql, $args);
        } else {
            $query = $this->db->query($sql);
        }

        $results = [];

        foreach ($query as $sensor) {
            $results[] = $this->createModel($sensor);
        }

        return $results;
    }

    /**
     * Gets the information on the given sensor id.
     * @param int $id is the sensor id.
     * @return SensorModel or false if not found.
     */
    public function getById($id)
    {
        $sql = "SELECT * FROM sensors WHERE id = :id";
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
     * Updates a sensor to be put into the sensors table.
     * @param array $data is the data from the parsed body
     * @return int for sensor ID or false if the query wasn't completed
     */
    public function update($data)
    {
        $sql = "UPDATE sensors
            SET bluetooth_address = :bluetooth_address,
                name = :name,
                description = :description
            WHERE id = :id";

        $sensor_model = $this->createModel($data);
        try {
            $sensor_model->validate();
        } catch (PatsException $e) {
            error_log($e);
            return false;
        }

        $args = [
            ':bluetooth_address' => $sensor_model->bluetooth_address,
            ':name' => $sensor_model->name,
            ':description' => $sensor_model->description,
            ':id' => $sensor_model->id
        ];

        $result = $this->db->execQuery($sql, $args);

        if ($result) {
            return $sensor_model->id;
        } else {
            return false;
        }
    }

    /**
     * Updates a sensor to be put into the sensors table.
     * @param array $data is the data from the parsed body
     * @return int for sensor ID or false if the query wasn't completed
     */
    public function updateActive($data)
    {
        $sql = "UPDATE sensors
            SET active = :active
            WHERE id = :id";

        if (isset($data['active'])) {
            $args = [
                ':id' => $data['id'],
                ':active' => $data['active']
            ];
        } else {
            return false;
        }

        $result = $this->db->execQuery($sql, $args);

        if ($result) {
            return $data['id'];
        } else {
            return false;
        }
    }

    //======================================================================
    // DELETE METHODS
    //======================================================================

    /**
     * Deletes a sensor in the sensors table.
     * @param array $data is the id
     * @return int for sensor ID or false if the query wasn't completed
     */
    public function delete($data)
    {
        $sql = "DELETE FROM sensors WHERE id = :id";

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
     * Creates a model for the sensor.
     * @return SensorModel model
     */
    private function createModel($data)
    {
        $model = new SensorModel();

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
        if (isset($data['active'])) {
            $model->active = boolval($data['active']);
        }

        return $model;
    }
}
