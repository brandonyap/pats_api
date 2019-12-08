<?php

use Helper\Acceptance;

/**
 * @group beacons
 */
class BeaconCest
{
    private $createdBeaconId;

    private $createBeaconWithDescriptionDataGood = [
        "bluetooth_address" => "12:34:56:78:90:12",
        "name" => "Test Beacon",
        "description" => "Blah Blah"
    ];

    private $updateBeaconWithDescriptionDataGood = [
        "bluetooth_address" => "11:22:33:44:55:66",
        "name" => "Test Beacon",
        "description" => "Blah Blah"
    ];

    private $createBeaconWithoutDescriptionDataGood = [
        "bluetooth_address" => "00:11:22:33:44:55",
        "name" => "Test Beacon 2",
        "description" => null
    ];

    private $createBeaconMissingAddressDataBad = [
        "bluetooth_address" => null,
        "name" => "Test Beacon",
        "description" => "Blah Blah"
    ];

    private $createBeaconMissingNameDataBad = [
        "bluetooth_address" => "12:34:56:78:90:12",
        "name" => null,
        "description" => "Blah Blah"
    ];

    /**
     * @group get
     */
    public function getAllBeaconsSucceeds(AcceptanceTester $I)
    {
        $I->sendGET('/beacons/all');
        $I->seeResponseCodeIs(200);
    }

    /**
     * @group post
     */
    public function createNewBeaconWithDescriptionSucceeds(AcceptanceTester $I)
    {
        $I->sendPOST('/beacons', $this->createBeaconWithDescriptionDataGood);
        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();

        $beaconId = $I->grabResponse();
        $beaconId = json_decode($beaconId, true);
        $this->createdBeaconId = $beaconId['data']['id'];
    }

    /**
     * @group post
     */
    public function createNewBeaconWithoutDescriptionSucceeds(AcceptanceTester $I)
    {
        $I->sendPOST('/beacons', $this->createBeaconWithoutDescriptionDataGood);
        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();
    }

    /**
     * This will fail because there cannot be duplicate bluetooth_address entries in the db.
     * @group post
     */
    public function createDuplicateBeaconFails(AcceptanceTester $I)
    {
        $I->sendPOST('/beacons', $this->createBeaconWithDescriptionDataGood);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
    }

    /**
     * @group post
     */
    public function createBeaconMissingAddressFails(AcceptanceTester $I)
    {
        $I->sendPOST('/beacons', $this->createBeaconMissingAddressDataBad);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
    }

    /**
     * @group post
     */
    public function createBeaconMissingNameFails(AcceptanceTester $I)
    {
        $I->sendPOST('/beacons', $this->createBeaconMissingNameDataBad);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
    }

    /**
     * @group get
     */
    public function getBeaconByIdSucceeds(AcceptanceTester $I)
    {
        $I->sendGET("/beacons/{$this->createdBeaconId}");
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['bluetooth_address' => $this->createBeaconWithDescriptionDataGood['bluetooth_address']]);
        $I->seeResponseContainsJson(['name' => $this->createBeaconWithDescriptionDataGood['name']]);
        $I->seeResponseContainsJson(['description' => $this->createBeaconWithDescriptionDataGood['description']]);
    }

    /**
     * @group put
     */
    public function updateBeaconByIdSucceeds(AcceptanceTester $I) 
    {
        $I->sendPUT("/beacons/{$this->createdBeaconId}", $this->updateBeaconWithDescriptionDataGood);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
