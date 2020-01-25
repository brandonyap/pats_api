<?php

namespace pats\Models;

use pats\Models\PatsModel;
use pats\Exceptions\PatsException;

class BeaconModel extends PatsModel
{
    public $uuid = null;
    public $name = null;
    public $description = "";

    /**
     * Validates whether or not the data in the model is valid.
     */
    public function validate()
    {
        if (!isset($this->uuid) || strlen($this->uuid) != 32) {
            throw new PatsException("Bluetooth Address not set or invalid");
        }
        if (!isset($this->name)) {
            throw new PatsException("Name not set");
        }

        return true;
    }
}
