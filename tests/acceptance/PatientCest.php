<?php

use Helper\Acceptance;

/**
 * @group sensors
 */
class PatientCest
{
    private $createdPatientId;

    private $createPatientDataGood = [
        "sensors_id" => null,
        "first_name" => "Blah",
        "last_name" => "Blah",
        "birthday" => "2000-01-01",
        "hospital_id" => 1,
        "physician" => "Test Test",
        "caretaker" => "Blah Blah"
    ];

    /**
     * @group get
     */
    public function getAllPatientsSucceeds(AcceptanceTester $I)
    {
        $I->sendGET('/patients/all');
        $I->seeResponseCodeIs(200);
    }

    /**
     * @group post
     */
    public function createNewPatientSucceeds(AcceptanceTester $I)
    {
        $createSensorData = [
            "bluetooth_address" => "11:22:33:44:55:66",
            "name" => "Test Patient Sensor",
            "description" => "Blah Blah"
        ];

        $I->sendPOST('/sensors', $createSensorData);
        $sensorId = $I->grabResponse();
        $sensorId = json_decode($sensorId, true);
        $sensorId = $sensorId['data']['id'];

        $this->createPatientDataGood['sensors_id'] = $sensorId;

        $I->sendPOST('/patients', $this->createPatientDataGood);
        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();

        $patientId = $I->grabResponse();
        $patientId = json_decode($patientId, true);
        $this->createdPatientId = $patientId['data']['id'];
    }
}
