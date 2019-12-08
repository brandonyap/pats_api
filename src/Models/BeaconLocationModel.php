<?php

namespace pats\Models;

use pats\Models\PatsModel;
use pats\Exceptions\PatsException;

class BeaconLocationModel extends PatsModel
{
    public $group_id = null;
    public $beacons_id = null;
    public $location_x = null;
    public $location_y = null;

    /**
     * Validates whether or not the data in the model is valid.
     */
    public function validate()
    {
        if (!isset($this->group_id)) {
            throw new PatsException("Group ID not set");
        }

        if (!isset($this->beacons_id)) {
            throw new PatsException("Beacon ID not set");
        }

        if (!isset($this->location_x)) {
            throw new PatsException("X Location not set");
        }

        if (!isset($this->location_y)) {
            throw new PatsException("Y Location not set");
        }

        return true;
    }
}
