<?php

namespace pats\Models;

use pats\Models\PatsModel;
use pats\Exceptions\PatsException;

class BeaconModel extends PatsModel
{
    public $bluetooth_address = null;
    public $name = null;
    public $description = "";

    /**
     * Validates whether or not the data in the model is valid.
     */
    public function validate()
    {
        if (!isset($this->bluetooth_address) || !preg_match('/^(?:[[:xdigit:]]{2}([-:]))(?:[[:xdigit:]]{2}\1){4}[[:xdigit:]]{2}$/', $this->bluetooth_address)) {
            throw new PatsException("Bluetooth Address not set or invalid");
        }
        if (!isset($this->name)) {
            throw new PatsException("Name not set");
        }

        return true;
    }
}
