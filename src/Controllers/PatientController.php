<?php

namespace pats\Controllers;

use pats\Controllers\RESTController;
use pats\Interfaces\PatientInterface;

class PatientController extends RESTController
{
    private $patient_interface;

    /**
     * Constructs the controller.
     */
    public function __construct()
    {
        $this->patient_interface = new PatientInterface();
    }

    //======================================================================
    // GET
    //======================================================================

    /**
     * GET /patients/all
     */
    public function get_index()
    {
        $result = $this->patient_interface->getAll();
        return $this->response(true, $result, 200);
    }

    /**
     * GET /patients/{id}
     */
    public function get_byId($data)
    {
        if (isset($data['id'])) {
            $result = $this->patient_interface->getById($data['id']);
            if ($result) {
                return $this->response(true, $result, 200);
            } else {
                return $this->response(false, "Patient not found.", 404);
            }
        } else {
            return $this->response(false, "Please provide an ID.", 400);
        }
    }

    //======================================================================
    // POST
    //======================================================================

    /**
     * POST /patients
     */
    public function post_index($data)
    {
        $result = $this->patient_interface->create($data);

        if (!$result) {
            return $this->response(false, "Error: Patient entry not created", 400);
        }

        return $this->response(true, $result, 201);
    }

    //======================================================================
    // PUT
    //======================================================================

    /**
     * PUT /patients/{id}
     */
    public function put_id($args, $data)
    {
        if (!isset($args['id'])) {
            return $this->response(false, "Error: Invalid ID. Patient not updated", 400);
        }

        $data['id'] = $args['id'];
        $result = $this->patient_interface->update($data);

        if (!$result) {
            return $this->response(false, "Error: Patient not updated", 400);
        }

        return $this->response(true, ['id' => $result], 200);
    }

    //======================================================================
    // DELETE
    //======================================================================

    /**
     * DELETE /patients/{id}
     */
    public function delete_id($data)
    {
        $result = $this->patient_interface->delete($data);

        if (!$result) {
            return $this->response(false, "Error: Patient not deleted", 400);
        }

        return $this->response(true, ['id' => $result], 200);
    }
}
