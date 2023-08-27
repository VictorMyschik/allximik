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
     * 'public',
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
    $public = array_rand(Hike::getPublicList());
    $hike->setPublic($public);
    $publicId = self::randomString(15);
    $hike->setPublicId($publicId);

    $hikeID = $hike->save_mr();


    // Asserts
    $hike = Hike::loadBy($hikeID);
    self::assertNotNull($hike);
    self::assertEquals($name, $hike->getName());
    self::assertEquals($description, $hike->getDescription());
    self::assertEquals($status, $hike->getStatus());
    self::assertEquals($userID, $hike->getUser()->id);
    self::assertEquals($countryID, $hike->getCountry()->id());
    self::assertEquals($hikeTypeID, $hike->getHikeType()->id());
    self::assertEquals($public, $hike->getPublic());
    self::assertEquals($publicId, $hike->getPublicId());


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
    $public = array_rand(Hike::getPublicList());
    $hike->setPublic($public);
    $publicId = self::randomString(15);
    $hike->setPublicId($publicId);

    $hikeID = $hike->save_mr();


    // Asserts
    $hike = Hike::loadBy($hikeID);
    self::assertNotNull($hike);
    self::assertEquals($name, $hike->getName());
    self::assertEquals($description, $hike->getDescription());
    self::assertEquals($status, $hike->getStatus());
    self::assertEquals($userID, $hike->getUser()->id);
    self::assertEquals($countryID, $hike->getCountry()->id());
    self::assertEquals($hikeTypeID, $hike->getHikeType()->id());
    self::assertEquals($public, $hike->getPublic());
    self::assertEquals($publicId, $hike->getPublicId());


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
