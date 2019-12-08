<?php

namespace pats\Models;

use pats\Models\PatsModel;
use pats\Exceptions\PatsException;

class RestrictedAreaModel extends PatsModel
{
    public $name = "";
    public $group_id = null;
    public $location_x_min = null;
    public $location_y_min = null;
    public $location_x_max = null;
    public $location_y_max = null;
    public $comments = "";

    /**
     * Validates whether or not the data in the model is valid.
     */
    public function validate()
    {
        if (!isset($this->group_id)) {
            throw new PatsException("Group ID not set");
        }

        if (!isset($this->location_x_min)) {
            throw new PatsException("X Min Location not set");
        }

        if (!isset($this->location_y_min)) {
            throw new PatsException("Y Min Location not set");
        }

        if (!isset($this->location_x_max)) {
            throw new PatsException("X Max Location not set");
        }

        if (!isset($this->location_y_max)) {
            throw new PatsException("Y Max Location not set");
        }

        return true;
    }
}
