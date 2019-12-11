<?php

namespace pats\Interfaces;

use pats\Interfaces\PatsInterface;
use pats\Models\PatientModel;
use pats\Exceptions\PatsException;
use pats\Interfaces\SensorInterface;

class PatientInterface extends PatsInterface
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
     * Creates a patient to be put into the patients table.
     * @param array $data is the data from the parsed body
     * @return int for the last insert ID or false if the query wasn't completed
     */
    public function create($data)
    {
        $sql = "INSERT INTO patients (sensors_id, first_name, last_name, birthday, hospital_id, physician, caretaker, comments)
        VALUES (:sensors_id, :first_name, :last_name, :birthday, :hospital_id, :physician, :caretaker, :comments)";

        $patient_model = $this->createModel($data);
        try {
            $patient_model->validate();
        } catch (PatsException $e) {
            error_log($e);
            return false;
        }

        // Check if sensor exists
        $sensor = $this->sensor_interface->getById($data['sensors_id']);
        if ($sensor === false) {
            return false;
        }

        $args = [
            ':sensors_id' => $patient_model->sensors_id,
            ':first_name' => $patient_model->first_name,
            ':last_name' => $patient_model->last_name,
            ':birthday' => $patient_model->birthday,
            ':hospital_id' => $patient_model->hospital_id,
            ':physician' => $patient_model->physician,
            ':caretaker' => $patient_model->caretaker,
            ':comments' => $patient_model->comments
        ];

        $result = $this->db->execQuery($sql, $args);

        if ($result) {
            $id = $this->db->lastInsertId();
            $this->sensor_interface->updateActive(['id' => $id, 'active' => true]);
            $patient_model->id = $id;
            return $patient_model;
        } else {
            return false;
        }
    }

    //======================================================================
    // READ METHODS
    //======================================================================

    /**
     * Gets all of the current patients in the database.
     * @return array of SensorModels's.
     */
    public function getAll()
    {
        $sql = "SELECT * FROM patients";

        $query = $this->db->query($sql);

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
        // $sql = "SELECT * FROM sensors WHERE id = :id";
        // $args[':id'] = $id;

        // $query = $this->db->query($sql, $args);

        // if (!$query) {
        //     return false;
        // }

        // if (count($query) > 0) {
        //     return $this->createModel($query[0]);
        // } else {
        //     return false;
        // }
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
        // $sql = "UPDATE sensors
        //     SET bluetooth_address = :bluetooth_address,
        //         name = :name,
        //         description = :description
        //     WHERE id = :id";

        // $sensor_model = $this->createModel($data);
        // try {
        //     $sensor_model->validate();
        // } catch (PatsException $e) {
        //     error_log($e);
        //     return false;
        // }

        // $args = [
        //     ':bluetooth_address' => $sensor_model->bluetooth_address,
        //     ':name' => $sensor_model->name,
        //     ':description' => $sensor_model->description,
        //     ':id' => $sensor_model->id
        // ];

        // $result = $this->db->execQuery($sql, $args);

        // if ($result) {
        //     return $sensor_model->id;
        // } else {
        //     return false;
        // }
    }

    /**
     * Updates a sensor to be put into the sensors table.
     * @param array $data is the data from the parsed body
     * @return int for sensor ID or false if the query wasn't completed
     */
    public function updateActive($data)
    {
        // $sql = "UPDATE sensors
        //     SET active = :active
        //     WHERE id = :id";

        // if (isset($data['active'])) {
        //     $args = [
        //         ':id' => $data['id'],
        //         ':active' => $data['active']
        //     ];
        // } else {
        //     return false;
        // }

        // $result = $this->db->execQuery($sql, $args);

        // if ($result) {
        //     return $data['id'];
        // } else {
        //     return false;
        // }
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
        // $sql = "DELETE FROM sensors WHERE id = :id";

        // $args = [':id' => $data['id']];

        // $result = $this->db->execQuery($sql, $args);

        // if ($result) {
        //     return $data['id'];
        // } else {
        //     return false;
        // }
    }

    //======================================================================
    // PRIVATE METHODS
    //======================================================================

    /**
     * Creates a model for the patient.
     * @return PatientModel model
     */
    private function createModel($data)
    {
        $model = new PatientModel();

        if (isset($data['id'])) {
            $model->id = $data['id'];
        }
        if (isset($data['sensors_id'])) {
            $model->sensors_id = $data['sensors_id'];
        }
        if (isset($data['first_name'])) {
            $model->first_name = $data['first_name'];
        }
        if (isset($data['last_name'])) {
            $model->last_name = $data['last_name'];
        }
        if (isset($data['birthday'])) {
            $model->birthday = $data['birthday'];
        }
        if (isset($data['hospital_id'])) {
            $model->hospital_id = $data['hospital_id'];
        }
        if (isset($data['physician'])) {
            $model->physician = $data['physician'];
        }
        if (isset($data['caretaker'])) {
            $model->caretaker = $data['caretaker'];
        }
        if (isset($data['date_created'])) {
            $model->date_created = $data['date_created'];
        }
        if (isset($data['comments'])) {
            $model->comments = $data['comments'];
        }

        return $model;
    }
}
