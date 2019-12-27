<?php

namespace pats\Models;

use pats\Models\PatsModel;
use pats\Exceptions\PatsException;

class BeaconGroupModel extends PatsModel
{
    public $name = null;
    public $description = "";
    public $active = false;
    public $map = null;

    /**
     * Validates whether or not the data in the model is valid.
     */
    public function validate()
    {
        if (!isset($this->name)) {
            throw new PatsException("Name not set");
        }

        if (!isset($this->active)) {
            throw new PatsException("Active not set");
        }

        return true;
    }
}
