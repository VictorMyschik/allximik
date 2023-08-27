<?php

namespace Models;

use App\Models\Hike;
use App\Models\HikeType;
use App\Models\Reference\Country;
use Tests\BaseTest;

class HikeTest extends BaseTest
{
  public function testHike()
  {
    /**
     * 'name',
     * 'description',
     * 'status',
     * 'user_id',
     * 'country_id',
     * 'hike_type_id',
     * */
    $hike = new Hike();

    $name = self::randomString(50);
    $hike->setName($name);
    $description = self::randomString(8000);
    $hike->setDescription($description);
    $status = array_rand(Hike::getStatusList());
    $hike->setStatus($status);
    $userID = 1;
    $hike->setUserID($userID);
    $countryID = self::randomIdFromClass(Country::class);
    $hike->setCountryID($countryID);
    $hikeTypeID = self::randomIdFromClass(HikeType::class);
    $hike->setHikeTypeID($hikeTypeID);

    $hikeID = $hike->save_mr();


    // Asserts
    $hike = Hike::loadBy($hikeID);
    self::assertNotNull($hike);
    $this->assertEquals($name, $hike->getName());
    $this->assertEquals($description, $hike->getDescription());
    $this->assertEquals($status, $hike->getStatus());
    $this->assertEquals($userID, $hike->getUser()->id);
    $this->assertEquals($countryID, $hike->getCountry()->id());
    $this->assertEquals($hikeTypeID, $hike->getHikeType()->id());


    // Update
    $name = self::randomString(50);
    $hike->setName($name);
    $description = self::randomString(8000);
    $hike->setDescription($description);
    $status = array_rand(Hike::getStatusList());
    $hike->setStatus($status);
    $userID = 1;
    $hike->setUserID($userID);
    $countryID = self::randomIdFromClass(Country::class);
    $hike->setCountryID($countryID);
    $hikeTypeID = self::randomIdFromClass(HikeType::class);
    $hike->setHikeTypeID($hikeTypeID);

    $hikeID = $hike->save_mr();


    // Asserts
    $hike = Hike::loadBy($hikeID);
    self::assertNotNull($hike);
    $this->assertEquals($name, $hike->getName());
    $this->assertEquals($description, $hike->getDescription());
    $this->assertEquals($status, $hike->getStatus());
    $this->assertEquals($userID, $hike->getUser()->id);
    $this->assertEquals($countryID, $hike->getCountry()->id());
    $this->assertEquals($hikeTypeID, $hike->getHikeType()->id());


    // Set null
    $hike->setDescription(null);
    $hikeID = $hike->save_mr();

    // Asserts
    $hike = Hike::loadBy($hikeID);
    self::assertNotNull($hike);
    $this->assertNull($hike->getDescription());


    // Delete
    $hike->delete_mr();
    $hike = Hike::loadBy($hikeID);
    self::assertNull($hike);
  }
}
