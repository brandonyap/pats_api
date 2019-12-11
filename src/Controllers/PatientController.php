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

    //======================================================================
    // DELETE
    //======================================================================
}
