<?php

namespace pats\Interfaces;

use pats\Interfaces\PatsInterface;
use pats\Models\BeaconLocationModel;
use pats\Exceptions\PatsException;

class BeaconLocationInterface extends PatsInterface
{
    /**
     * Constructs the class to use PatsInterface.
     */
    public function __construct()
    {
        parent::__construct();
    }

    //======================================================================
    // PRIVATE METHODS
    //======================================================================

    /**
     * Creates a model for the beacon location.
     * @return BeaconLocationModel model
     */
    private function createModel($data)
    {
        $model = new BeaconLocationModel();

        if (isset($data['id'])) {
            $model->id = intval($data['id']);
        }
        if (isset($data['group_id'])) {
            $model->group_id = $data['group_id'];
        }
        if (isset($data['beacons_id'])) {
            $model->beacons_id = $data['beacons_id'];
        }
        if (isset($data['location_x'])) {
            $model->location_x = boolval($data['location_x']);
        }
        if (isset($data['location_y'])) {
            $model->map = $data['location_y'];
        }

        return $model;
    }
}
