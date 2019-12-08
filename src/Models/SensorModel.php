<?php

namespace pats\Models;

use pats\Models\PatsModel;
use pats\Exceptions\PatsException;

class SensorModel extends PatsModel
{
    public $bluetooth_address = null;
    public $name = null;
    public $description = "";
    public $active = false; // true means being used by a patient and false means not being used.

    /**
     * Validates whether or not the data in the model is valid.
     */
    public function validate()
    {
        if (!isset($this->bluetooth_address)) {
            throw new PatsException("Bluetooth Address not set");
        }
        if (!isset($this->name)) {
            throw new PatsException("Name not set");
        }

        return true;
    }
}
