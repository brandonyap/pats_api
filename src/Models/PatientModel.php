<?php

namespace pats\Models;

use pats\Models\PatsModel;
use pats\Exceptions\PatsException;

class PatientModel extends PatsModel
{
    public $sensors_id = null;
    public $first_name = null;
    public $last_name = null;
    public $birthday = null;
    public $hospital_id = null;
    public $physician = null;
    public $caretaker = null;
    public $date_created = null;
    public $comments = "";

    /**
     * Validates whether or not the data in the model is valid.
     */
    public function validate()
    {
        if (!isset($this->sensors_id)) {
            throw new PatsException("Sensor ID not set");
        }

        if (!isset($this->first_name)) {
            throw new PatsException("First Name not set");
        }

        if (!isset($this->last_name)) {
            throw new PatsException("Last Name not set");
        }

        if (!isset($this->birthday)) {
            throw new PatsException("Birthday not set");
        }

        if (!isset($this->hospital_id)) {
            throw new PatsException("Hospital ID not set");
        }

        if (!isset($this->physician)) {
            throw new PatsException("Physician not set");
        }

        if (!isset($this->caretaker)) {
            throw new PatsException("Caretaker not set");
        }

        return true;
    }
}
