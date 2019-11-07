<?php 

class BeaconCest
{
    /**
     * @group get
     */
    public function getAllBeaconsSucceeds(AcceptanceTester $I)
    {
        $I->sendGET('/beacons');
        $I->seeResponseCodeIs(200);
    }
}
