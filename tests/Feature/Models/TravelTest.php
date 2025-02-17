<?php

namespace Tests\Feature\Models;

use App\Models\Reference\Country;
use App\Models\Travel;
use App\Models\TravelType;
use Tests\TestBase;

class TravelTest extends TestBase
{
    public function testTravel()
    {
        /**
         * 'name',
         * 'description',
         * 'status',
         * 'user_id',
         * 'country_id',
         * 'travel_type_id',
         * 'public',
         * */
        $travel = new Travel();

        $name = self::randomString(50);
        $travel->setName($name);
        $description = self::randomString(8000);
        $travel->setDescription($description);
        $status = array_rand(Travel::getStatusList());
        $travel->setStatus($status);
        $userID = 1;
        $travel->setUserID($userID);
        $countryID = self::randomIdFromClass(Country::class);
        $travel->setCountryID($countryID);
        $travelTypeID = self::randomIdFromClass(TravelType::class);
        $travel->setTravelTypeID($travelTypeID);
        $visible = array_rand(Travel::getVisibleKindList());
        $travel->setVisibleKind($visible);
        $publicId = self::randomString(15);
        $travel->setPublicId($publicId);

        $travelID = $travel->save_mr();


        // Asserts
        $travel = Travel::loadBy($travelID);
        self::assertNotNull($travel);
        self::assertEquals($name, $travel->getName());
        self::assertEquals($description, $travel->getDescription());
        self::assertEquals($status, $travel->getStatus());
        self::assertEquals($userID, $travel->getUser()->id);
        self::assertEquals($countryID, $travel->getCountry()->id());
        self::assertEquals($travelTypeID, $travel->getTravelType()->id());
        self::assertEquals($visible, $travel->getVisibleKind());
        self::assertEquals($publicId, $travel->getPublicId());


        // Update
        $name = self::randomString(50);
        $travel->setName($name);
        $description = self::randomString(8000);
        $travel->setDescription($description);
        $status = array_rand(Travel::getStatusList());
        $travel->setStatus($status);
        $userID = 1;
        $travel->setUserID($userID);
        $countryID = self::randomIdFromClass(Country::class);
        $travel->setCountryID($countryID);
        $travelTypeID = self::randomIdFromClass(TravelType::class);
        $travel->setTravelTypeID($travelTypeID);
        $visible = array_rand(Travel::getVisibleKindList());
        $travel->setVisibleKind($visible);
        $publicId = self::randomString(15);
        $travel->setPublicId($publicId);

        $travelID = $travel->save_mr();


        // Asserts
        $travel = Travel::loadBy($travelID);
        self::assertNotNull($travel);
        self::assertEquals($name, $travel->getName());
        self::assertEquals($description, $travel->getDescription());
        self::assertEquals($status, $travel->getStatus());
        self::assertEquals($userID, $travel->getUser()->id);
        self::assertEquals($countryID, $travel->getCountry()->id());
        self::assertEquals($travelTypeID, $travel->getTravelType()->id());
        self::assertEquals($visible, $travel->getVisibleKind());
        self::assertEquals($publicId, $travel->getPublicId());


        // Set null
        $travel->setDescription(null);
        $travelID = $travel->save_mr();


        // Asserts
        $travel = Travel::loadBy($travelID);
        self::assertNotNull($travel);
        $this->assertNull($travel->getDescription());


        // Delete
        $travel->delete_mr();
        $travel = Travel::loadBy($travelID);
        self::assertNull($travel);
    }
}
