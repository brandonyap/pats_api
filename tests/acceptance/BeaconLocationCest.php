<?php

use Helper\Acceptance;

/**
 * @group beacons_location
 */
class BeaconLocationCest
{
    private $createdBeaconId;
    private $createdBeaconGroupId;
    private $createdBeaconLocationId;

    private $createBeaconWithDescriptionDataGood = [
        "uuid" => "abcdefgh12345678abcdefgh12345678",
        "name" => "Test Beacon",
        "description" => "Blah Blah"
    ];

    private $createBeaconGroupWithDescriptionDataGood = [
        "name" => "Test Beacon",
        "description" => "Blah Blah"
    ];

    private $createdBeaconLocationDataGood = [
        "group_id" => null,
        "location_x" => 3.0,
        "location_y" => 5.0
    ];

    /**
     * @group post
     */
    public function setupBeaconAndBeaconGroupForTest(AcceptanceTester $I)
    {
        $I->sendPOST('/beacons', $this->createBeaconWithDescriptionDataGood);
        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();

        $beaconId = $I->grabResponse();
        $beaconId = json_decode($beaconId, true);
        $this->createdBeaconId = $beaconId['data']['id'];

        $I->sendPOST('/beacons/group', $this->createBeaconGroupWithDescriptionDataGood);
        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();

        $beaconId = $I->grabResponse();
        $beaconId = json_decode($beaconId, true);
        $this->createdBeaconGroupId = $beaconId['data']['id'];

        $this->createdBeaconLocationDataGood['group_id'] = $this->createdBeaconGroupId;
    }

    /**
     * @group post
     */
    public function addBeaconLocation(AcceptanceTester $I)
    {
        $I->sendPOST("/beacons/{$this->createdBeaconId}/location", $this->createdBeaconLocationDataGood);
        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();

        $beaconId = $I->grabResponse();
        $beaconId = json_decode($beaconId, true);
        $this->createdBeaconLocationId = $beaconId['data']['id'];
    }
}