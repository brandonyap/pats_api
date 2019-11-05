<?php

namespace pats\Interfaces;

use pats\Interfaces\PatsInterface;
use pats\Models\BeaconModel;

class BeaconInterface extends PatsInterface
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create($data)
    {
        $sql = "INSERT INTO beacons (bluetooth_address, name, description)
        VALUES (:bluetooth_address, :name, :description)";

        $beacon_model = $this->createModel($data);
        try {
            $beacon_model->validate();
        } catch (PatsException $e) {
            return [false, $e];
        }

        $args = [
            ':bluetooth_address' => $beacon_model->bluetooth_address,
            ':name' => $beacon_model->name,
            ':description' => $beacon_model->description
        ];

        $result = $this->db->execQuery($sql, $args);

        if ($result) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    private function createModel($data)
    {
        $model = new BeaconModel();

        if (isset($data['id'])) {
            $model->id = $data['id'];
        }
        if (isset($data['bluetooth_address'])) {
            $model->bluetooth_address = $data['bluetooth_address'];
        }
        if (isset($data['name'])) {
            $model->name = $data['name'];
        }
        if (isset($data['description'])) {
            $model->description = $data['description'];
        }

        return $model;
    }
}