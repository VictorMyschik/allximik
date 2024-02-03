<?php

namespace Tests\Feature\Models;

use App\Models\TravelImage;
use App\Models\User;
use Tests\TestBase;

class TravelImageTest extends TestBase
{
  public function testTravel()
  {
    /**
     * travel_id
     * name
     * original_name
     * size
     * sort
     * description
     * hash
     * user_id
     * kind
     * group
     * */
    $travel = $this->createTravel(self::randomIdFromClass(User::class));


    $travelImage = new TravelImage();
    $travelImage->setTravelID($travel->id());
    $kind = array_rand(TravelImage::getKindList());
    $travelImage->setKind($kind);
    $name = self::randomString(50);
    $travelImage->setName($name);
    $originalName = self::randomString(50);
    $travelImage->setOriginalName($originalName);
    $size = rand(1, 1000);
    $travelImage->setSize($size);
    $sort = rand(1, 1000);
    $travelImage->setSort($sort);
    $description = self::randomString(50);
    $travelImage->setDescription($description);
    $hash = self::randomString(50);
    $travelImage->setHash($hash);
    $group = self::randomString(50);
    $travelImage->setGroup($group);
    $userID = self::randomIdFromClass(User::class);
    $travelImage->setUserID($userID);

    $travelImageID = $travelImage->save_mr();


    // Asserts
    $travelImage = TravelImage::loadBy($travelImageID);
    self::assertNotNull($travelImage);
    self::assertEquals($name, $travelImage->getName());
    self::assertEquals($kind, $travelImage->getKind());
    self::assertEquals($originalName, $travelImage->getOriginalName());
    self::assertEquals($size, $travelImage->getSize());
    self::assertEquals($sort, $travelImage->getSort());
    self::assertEquals($description, $travelImage->getDescription());
    self::assertEquals($hash, $travelImage->getHash());
    self::assertEquals($group, $travelImage->getGroup());
    self::assertEquals($userID, $travelImage->getUser()->id());


    // Update
    $travelImage->setTravelID($travel->id());
    $kind = array_rand(TravelImage::getKindList());
    $travelImage->setKind($kind);
    $name = self::randomString(50);
    $travelImage->setName($name);
    $originalName = self::randomString(50);
    $travelImage->setOriginalName($originalName);
    $size = rand(1, 1000);
    $travelImage->setSize($size);
    $sort = rand(1, 1000);
    $travelImage->setSort($sort);
    $description = self::randomString(50);
    $travelImage->setDescription($description);
    $hash = self::randomString(50);
    $travelImage->setHash($hash);
    $group = self::randomString(50);
    $travelImage->setGroup($group);
    $userID = self::randomIdFromClass(User::class);
    $travelImage->setUserID($userID);

    $travelImageID = $travelImage->save_mr();


    // Asserts
    $travelImage = TravelImage::loadBy($travelImageID);
    self::assertNotNull($travelImage);
    self::assertEquals($name, $travelImage->getName());
    self::assertEquals($kind, $travelImage->getKind());
    self::assertEquals($originalName, $travelImage->getOriginalName());
    self::assertEquals($size, $travelImage->getSize());
    self::assertEquals($sort, $travelImage->getSort());
    self::assertEquals($description, $travelImage->getDescription());
    self::assertEquals($hash, $travelImage->getHash());
    self::assertEquals($group, $travelImage->getGroup());
    self::assertEquals($userID, $travelImage->getUser()->id());

    // Delete
    $travelImage->delete_mr();
    $travelImage = TravelImage::loadBy($travelImageID);
    self::assertNull($travelImage);
  }
}
