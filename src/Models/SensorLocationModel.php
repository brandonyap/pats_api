<?php

namespace pats\Models;

use pats\Models\PatsModel;
use pats\Exceptions\PatsException;

class SensorLocationModel extends PatsModel
{
    public $sensors_id = null;
    public $location_x = null;
    public $location_y = null;
    public $timestamp = "";

    /**
     * Validates whether or not the data in the model is valid.
     */
    public function validate()
    {
        if (!isset($this->sensors_id)) {
            throw new PatsException("Sensor ID not set");
        }
        if (!isset($this->location_x)) {
            throw new PatsException("Location X not set");
        }
        if (!isset($this->location_y)) {
            throw new PatsException("Location Y not set");
        }

        return true;
    }
}
