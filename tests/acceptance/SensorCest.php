<?php

use Helper\Acceptance;

/**
 * @group sensors
 */
class SensorCest
{
    private $createdSensorId;

    private $createSensorWithDescriptionDataGood = [
        "bluetooth_address" => "12:34:56:78:90:12",
        "name" => "Test Sensor",
        "description" => "Blah Blah"
    ];

    private $updateSensorWithDescriptionDataGood = [
        "bluetooth_address" => "22:22:33:44:55:66",
        "name" => "Test Sensor",
        "description" => "Blah Blah"
    ];

    private $createSensorWithoutDescriptionDataGood = [
        "bluetooth_address" => "00:11:22:33:44:55",
        "name" => "Test Sensor 2",
        "description" => null
    ];

    private $createSensorMissingAddressDataBad = [
        "bluetooth_address" => null,
        "name" => "Test Sensor",
        "description" => "Blah Blah"
    ];

    private $createSensorMissingNameDataBad = [
        "bluetooth_address" => "12:34:56:78:90:12",
        "name" => null,
        "description" => "Blah Blah"
    ];

    private $createSensorBadBluetoothAddressDataBad = [
        "bluetooth_address" => "22:22:33:",
        "name" => "Test Sensor",
        "description" => "Blah Blah"
    ];

    /**
     * @group get
     */
    public function getAllSensorsSucceeds(AcceptanceTester $I)
    {
        $I->sendGET('/sensors/all');
        $I->seeResponseCodeIs(200);
    }

    /**
     * @group post
     */
    public function createNewSensorsWithDescriptionSucceeds(AcceptanceTester $I)
    {
        $I->sendPOST('/sensors', $this->createSensorWithDescriptionDataGood);
        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();

        $sensorId = $I->grabResponse();
        $sensorId = json_decode($sensorId, true);
        $this->createdSensorId = $sensorId['data']['id'];
    }

    /**
     * @group post
     */
    public function createNewSensorWithoutDescriptionSucceeds(AcceptanceTester $I)
    {
        $I->sendPOST('/sensors', $this->createSensorWithoutDescriptionDataGood);
        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();
    }

    /**
     * This will fail because there cannot be duplicate bluetooth_address entries in the db.
     * @group post
     */
    public function createDuplicateSensorFails(AcceptanceTester $I)
    {
        $I->sendPOST('/sensors', $this->createSensorWithDescriptionDataGood);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
    }

    /**
     * @group post
     */
    public function createSensorMissingAddressFails(AcceptanceTester $I)
    {
        $I->sendPOST('/sensors', $this->createSensorMissingAddressDataBad);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
    }

    /**
     * @group post
     */
    public function createSensorBadAddressFails(AcceptanceTester $I)
    {
        $I->sendPOST('/sensors', $this->createSensorBadBluetoothAddressDataBad);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
    }

    /**
     * @group post
     */
    public function createSensorMissingNameFails(AcceptanceTester $I)
    {
        $I->sendPOST('/sensors', $this->createSensorMissingNameDataBad);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
    }

    /**
     * @group get
     */
    public function getSensorByIdSucceeds(AcceptanceTester $I)
    {
        $I->sendGET("/sensors/{$this->createdSensorId}");
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['bluetooth_address' => $this->createSensorWithDescriptionDataGood['bluetooth_address']]);
        $I->seeResponseContainsJson(['name' => $this->createSensorWithDescriptionDataGood['name']]);
        $I->seeResponseContainsJson(['description' => $this->createSensorWithDescriptionDataGood['description']]);
    }

    /**
     * @group get
     */
    public function getSensorByMissingIdFails(AcceptanceTester $I)
    {
        $I->sendGET("/sensors/100000");
        $I->seeResponseCodeIs(404);
        $I->seeResponseIsJson();
    }

    /**
     * @group put
     */
    public function updateSensorByIdSucceeds(AcceptanceTester $I) 
    {
        $I->sendPUT("/sensors/{$this->createdSensorId}", $this->updateSensorWithDescriptionDataGood);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * @group delete
     */
    public function deleteSensorByIdSucceeds(AcceptanceTester $I) 
    {
        $I->sendDELETE("/sensors/{$this->createdSensorId}");
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
