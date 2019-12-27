<?php

use Helper\Acceptance;

/**
 * @group beacons
 */
class BeaconGroupCest
{
    private $createdBeaconId;

    private $createBeaconWithDescriptionDataGood = [
        "name" => "Test Beacon",
        "description" => "Blah Blah"
    ];

    private $updateBeaconWithDescriptionDataGood = [
        "name" => "Test Beacon",
        "description" => "Blah Blah"
    ];

    private $createBeaconWithoutDescriptionDataGood = [
        "name" => "Test Beacon 2",
        "description" => null
    ];

    private $createBeaconMissingNameDataBad = [
        "name" => null,
        "description" => "Blah Blah"
    ];

    /**
     * @group get
     */
    public function getAllBeaconGroupsSucceeds(AcceptanceTester $I)
    {
        $I->sendGET('/beacons/group/all');
        $I->seeResponseCodeIs(200);
    }

    /**
     * @group post
     */
    public function createNewBeaconGroupWithDescriptionSucceeds(AcceptanceTester $I)
    {
        $I->sendPOST('/beacons/group', $this->createBeaconWithDescriptionDataGood);
        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();

        $beaconId = $I->grabResponse();
        $beaconId = json_decode($beaconId, true);
        $this->createdBeaconId = $beaconId['data']['id'];
    }

    /**
     * @group post
     */
    public function createNewBeaconGroupWithoutDescriptionSucceeds(AcceptanceTester $I)
    {
        $I->sendPOST('/beacons/group', $this->createBeaconWithoutDescriptionDataGood);
        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();
    }

    /**
     * This will fail because there cannot be duplicate bluetooth_address entries in the db.
     * @group post
     */
    public function createDuplicateBeaconGroupFails(AcceptanceTester $I)
    {
        $I->sendPOST('/beacons/group', $this->createBeaconWithDescriptionDataGood);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
    }

    /**
     * @group post
     */
    public function createBeaconGroupMissingNameFails(AcceptanceTester $I)
    {
        $I->sendPOST('/beacons/group', $this->createBeaconMissingNameDataBad);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
    }

    /**
     * @group get
     */
    public function getBeaconGroupByIdSucceeds(AcceptanceTester $I)
    {
        $I->sendGET("/beacons/group/{$this->createdBeaconId}");
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['name' => $this->createBeaconWithDescriptionDataGood['name']]);
        $I->seeResponseContainsJson(['description' => $this->createBeaconWithDescriptionDataGood['description']]);
    }

    /**
     * @group put
     */
    public function updateBeaconGroupByIdSucceeds(AcceptanceTester $I) 
    {
        $I->sendPUT("/beacons/group/{$this->createdBeaconId}", $this->updateBeaconWithDescriptionDataGood);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    /**
     * @group delete
     */
    public function deleteBeaconGroupByIdSucceeds(AcceptanceTester $I) 
    {
        $I->sendDELETE("/beacons/group/{$this->createdBeaconId}");
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
