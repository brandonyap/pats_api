<?php

namespace pats\Interfaces;

use pats\Interfaces\PatsInterface;
use pats\Models\PatientModel;
use pats\Models\PatientLocationModel;
use pats\Exceptions\PatsException;
use pats\Interfaces\SensorInterface;
use pats\Interfaces\SensorLocationInterface;

class PatientInterface extends PatsInterface
{
    private $sensor_interface;
    private $sensor_location_interface;

    /**
     * Constructs the class to use PatsInterface.
     */
    public function __construct()
    {
        parent::__construct();
        $this->sensor_interface = new SensorInterface();
        $this->sensor_location_interface = new SensorLocationInterface();
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
            $this->sensor_interface->updateActive(['id' => $patient_model->sensors_id, 'active' => true]);
            $patient_model->id = intval($id);
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
     * @return array of PatientModel's.
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
     * Gets the information on the given patient id.
     * @param int $id is the patient id.
     * @return PatientModel or false if not found.
     */
    public function getById($id)
    {
        $sql = "SELECT * FROM patients WHERE id = :id";
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

    public function getPatientLocation($id)
    {
        $patient = $this->getById($id);
        $sensor_location = $this->sensor_location_interface->getCurrentLocationById($patient->sensors_id);

        if ($sensor_location) {
            return $this->createLocationModel($patient, $sensor_location);
        } else {
            return false;
        }
    }

    public function getAllPatientLocations()
    {
        $sql = "SELECT 
                    patients.id, 
                    patients.sensors_id, 
                    patients.first_name, 
                    patients.last_name,
                    sensors_locations.location_x,
                    sensors_locations.location_y,
                    sensors_locations.last_updated
                FROM patients 
                LEFT JOIN sensors_locations 
                ON patients.sensors_id=sensors_locations.sensors_id";
        
        $sensor_locations = $this->db->query($sql);

        if (!$sensor_locations) {
            return false;
        }

        $results = [];
        foreach($sensor_locations as $sl) {
            $results[] = $this->createLocationModelFromQuery($sl);
        }

        return $results;
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
        $sql = "UPDATE patients
            SET sensors_id = :sensors_id,
                first_name = :first_name,
                last_name = :last_name,
                birthday = :birthday,
                hospital_id = :hospital_id,
                physician = :physician,
                caretaker = :caretaker,
                comments = :comments
            WHERE id = :id";

        $patient_model = $this->createModel($data);
        try {
            $patient_model->validate();
        } catch (PatsException $e) {
            error_log($e);
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
            ':comments' => $patient_model->comments,
            ':id' => $patient_model->id
        ];

        $patient = $this->getById($patient_model->id);
        $result = $this->db->execQuery($sql, $args);

        if ($result) {
            if ($patient->sensors_id != $patient_model->sensors_id) {
                $this->sensor_interface->updateActive(['id' => $patient->sensors_id, 'active' => false]);
                $this->sensor_interface->updateActive(['id' => $patient_model->sensors_id, 'active' => true]);
            }
            return $patient_model;
        } else {
            return false;
        }
    }

    //======================================================================
    // DELETE METHODS
    //======================================================================

    /**
     * Deletes a patient in the patients table.
     * @param array $data is the id
     * @return int for patient ID or false if the query wasn't completed
     */
    public function delete($data)
    {
        $sql = "DELETE FROM patients WHERE id = :id";

        $args = [':id' => $data['id']];

        $patient = $this->getById($data['id']);
        $result = $this->db->execQuery($sql, $args);

        if ($result) {
            $this->sensor_interface->updateActive(['id' => $patient->sensors_id, 'active' => false]);
            return $data['id'];
        } else {
            return false;
        }
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
            $model->id = intval($data['id']);
        }
        if (isset($data['sensors_id'])) {
            $model->sensors_id = intval($data['sensors_id']);
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

    private function createLocationModel($patient_model, $sensor_location_model)
    {
        $model = new PatientLocationModel();

        $model->id = $patient_model->id;
        $model->sensors_id = $patient_model->sensors_id;
        $model->first_name = $patient_model->first_name;
        $model->last_name = $patient_model->last_name;
        $model->location_x = $sensor_location_model->location_x;
        $model->location_y = $sensor_location_model->location_y;
        $model->timestamp = $sensor_location_model->timestamp;

        return $model;
    }

    private function createLocationModelFromQuery($query)
    {
        $model = new PatientLocationModel();

        $model->id = intval($query["id"]);
        $model->sensors_id = intval($query["sensors_id"]);
        $model->first_name = $query["first_name"];
        $model->last_name = $query["last_name"];
        $model->location_x = floatval($query["location_x"]);
        $model->location_y = floatval($query["location_y"]);
        $model->timestamp = $query["last_updated"];

        return $model;
    }
}
