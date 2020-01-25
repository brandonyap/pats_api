<?php

use Helper\Acceptance;

/**
 * @group beacons
 */
class BeaconCest
{
    private $createdBeaconId;

    private $createBeaconWithDescriptionDataGood = [
        "uuid" => "abcdefgh12345678abcdefgh12345678",
        "name" => "Test Beacon",
        "description" => "Blah Blah"
    ];

    private $updateBeaconWithDescriptionDataGood = [
        "uuid" => "abcdefgh12345678abcdefgh12345678",
        "name" => "Test Beacon",
        "description" => "Blah Blah"
    ];

    private $createBeaconWithoutDescriptionDataGood = [
        "uuid" => "12345678abcdefgh12345678abcdefgh",
        "name" => "Test Beacon 2",
        "description" => null
    ];

    private $createBeaconMissingAddressDataBad = [
        "uuid" => null,
        "name" => "Test Beacon",
        "description" => "Blah Blah"
    ];

    private $createBeaconMissingNameDataBad = [
        "uuid" => "12345678abcdefgh1234567801cdefgh",
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
        $I->seeResponseContainsJson(['uuid' => $this->createBeaconWithDescriptionDataGood['uuid']]);
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

    /**
     * @group delete
     */
    public function deleteBeaconByIdSucceeds(AcceptanceTester $I) 
    {
        $I->sendDELETE("/beacons/{$this->createdBeaconId}");
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
