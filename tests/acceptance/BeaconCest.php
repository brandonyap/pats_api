<?php 

class BeaconCest
{
    private $createBeaconDataGood = [
        "bluetooth_address" => "12:34:56:78:90:12",
        "name" => "Test Beacon",
        "description" => "Blah Blah"
    ];

    /**
     * @group get
     */
    public function getAllBeaconsSucceeds(AcceptanceTester $I)
    {
        $I->sendGET('/beacons');
        $I->seeResponseCodeIs(200);
    }

    /**
     * @group post
     */
    public function createBeaconSucceeds(AcceptanceTester $I)
    {
        $I->sendPOST('/beacons', $this->createBeaconDataGood);
        $I->seeResponseCodeIs(201);
    }
}
