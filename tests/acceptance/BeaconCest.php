<?php 

/**
 * @group beacons
 */
class BeaconCest
{
    private $createdBeaconId;

    private $createBeaconDataGood = [
        "bluetooth_address" => "12:34:56:78:90:12",
        "name" => "Test Beacon",
        "description" => "Blah Blah"
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
    public function createNewBeaconSucceeds(AcceptanceTester $I)
    {
        $I->sendPOST('/beacons', $this->createBeaconDataGood);
        $I->seeResponseCodeIs(201);
        $I->seeResponseContainsJson();

        $beaconId = $I->grabResponse();
        $beaconId = json_decode($beaconId, true);
        $this->createdBeaconId = $beaconId['data']['id'];
    }

    /**
     * This will fail because there cannot be duplicate bluetooth_address entries in the db.
     * @group post
     */
    public function createDuplicateBeaconFails(AcceptanceTester $I)
    {
        $I->sendPOST('/beacons', $this->createBeaconDataGood);
        $I->seeResponseCodeIs(400);
        $I->seeResponseContainsJson();
    }

    /**
     * @group post
     */
    public function createBeaconMissingAddressFails(AcceptanceTester $I)
    {
        $I->sendPOST('/beacons', $this->createBeaconMissingAddressDataBad);
        $I->seeResponseCodeIs(400);
        $I->seeResponseContainsJson();
    }

    /**
     * @group post
     */
    public function createBeaconMissingNameFails(AcceptanceTester $I)
    {
        $I->sendPOST('/beacons', $this->createBeaconMissingNameDataBad);
        $I->seeResponseCodeIs(400);
        $I->seeResponseContainsJson();
    }
}
