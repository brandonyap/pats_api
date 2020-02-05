<?php

namespace pats\Interfaces;

use pats\Interfaces\PatsInterface;
use pats\Interfaces\SensorInterface;
use pats\Models\SensorLocationModel;
use pats\Exceptions\PatsException;

class SensorLocationInterface extends PatsInterface
{
    private $sensor_interface;
    /**
     * Constructs the class to use PatsInterface.
     */
    public function __construct()
    {
        parent::__construct();
        $this->sensor_interface = new SensorInterface();
    }

    //======================================================================
    // CREATE METHODS
    //======================================================================
    
    /**
     * Creates a sensor location to be put into the sensors_location table.
     * @param array $data is the data from the parsed body
     * @return int for the last insert ID or false if the query wasn't completed
     */
    public function create($data)
    {
        // Find the sensors_address from the sensors_table
        if (isset($data['sensors_address'])) {
            $sensor = $this->sensor_interface->getByBluetoothAddress($data['sensors_address']);

            if ($sensor && $sensor->active) {
                $data['sensors_id'] = $sensor->id;
            } else {
                return false;
            }
        } else {
            return false;
        }

        // Insert into location history first then update into last location
        $sql = "INSERT INTO sensors_locations_history (sensors_id, location_x, location_y)
                VALUES (:sensors_id, :location_x, :location_y)";

        $sensor_location_model = $this->createModel($data);
        try {
            $sensor_location_model->validate();
        } catch (PatsException $e) {
            error_log($e);
            return false;
        }

        $args = [
            ':sensors_id' => $sensor_location_model->sensors_id,
            ':location_x' => $sensor_location_model->location_x,
            ':location_y' => $sensor_location_model->location_y,
        ];

        $this->db->beginTransaction();
        $result = $this->db->execQuery($sql, $args);

        if ($result) {
            // Complete 2nd part and Insert or Update the current sensors_lcoations table.
            $sql_2 = "REPLACE INTO sensors_locations (sensors_id, location_x, location_y)
                        VALUES (:sensors_id, :location_x, :location_y)";
            $result = $this->db->execQuery($sql_2, $args);

            if ($result) {
                $this->db->commit();
                return true;
            }
        }

        // If anything fails then rollback the insert queries
        $this->db->rollBack();
        return false;
    }

    //======================================================================
    // READ METHODS
    //======================================================================

    /**
     * Gets current location of sensor.
     * @param int $sensor_id is the sensor id for the search.
     * @return SensorModel of result.
     */
    public function getCurrentLocationById($sensor_id)
    {
        $sql = "SELECT * FROM sensors_locations WHERE sensors_id = :sensors_id";

        $args = [':sensors_id' => $sensor_id];

        $result = $this->db->query($sql, $args);

        if (count($result) > 0) {
            return $this->createModel($result[0]);
        } else {
            return false;
        }
    }

    //======================================================================
    // PRIVATE METHODS
    //======================================================================

    /**
     * Creates a model for the sensor location.
     * @return SensorLocationModel model
     */
    private function createModel($data)
    {
        $model = new SensorLocationModel();

        if (isset($data['id'])) {
            $model->id = intval($data['id']);
        }
        if (isset($data['sensors_id'])) {
            $model->sensors_id = intval($data['sensors_id']);
        }
        if (isset($data['location_x'])) {
            $model->location_x = floatval($data['location_x']);
        }
        if (isset($data['location_y'])) {
            $model->location_y = floatval($data['location_y']);
        }
        if (isset($data['timestamp'])) {
            $model->timestamp = $data['timestamp'];
        }
        if (isset($data['last_updated'])) {
            $model->timestamp = $data['last_updated'];
        }

        return $model;
    }
}
