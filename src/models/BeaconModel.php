<?php

namespace pats\Models;

use pats\Models\PatsModel;
use pats\Exceptions\PatsException;

class BeaconModel extends PatsModel
{
    public $bluetooth_address = null;
    public $name = null;
    public $description = "";

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
