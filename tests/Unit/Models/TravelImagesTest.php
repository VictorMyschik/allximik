<?php

namespace Models;

use App\Models\TravelImage;
use App\Models\User;
use Tests\BaseTest;

class TravelImagesTest extends BaseTest
{
  public function testTravel()
  {
    /**
     * 'travel_id',
     * 'kind',
     * 'name',
     * */
    $travelImage = $this->createTravel(self::randomIdFromClass(User::class));


    $travelImage = new TravelImage();
    $travelImage->setTravelID($travelImage->id());
    $kind = array_rand(TravelImage::getKindList());
    $travelImage->setKind($kind);
    $name = self::randomString(50);
    $travelImage->setName($name);

    $travelImageID = $travelImage->save_mr();


    // Asserts
    $travelImage = TravelImage::loadBy($travelImageID);
    self::assertNotNull($travelImage);
    self::assertEquals($name, $travelImage->getName());
    self::assertEquals($kind, $travelImage->getKind());


    // Update
    $travelImage->setTravelID($travelImage->id());
    $kind = array_rand(TravelImage::getKindList());
    $travelImage->setKind($kind);
    $name = self::randomString(50);
    $travelImage->setName($name);

    $travelImageID = $travelImage->save_mr();


    // Asserts
    $travelImage = TravelImage::loadBy($travelImageID);
    self::assertNotNull($travelImage);
    self::assertEquals($name, $travelImage->getName());
    self::assertEquals($kind, $travelImage->getKind());


    // Delete
    $travelImage->delete_mr();
    $travelImage = TravelImage::loadBy($travelImageID);
    self::assertNull($travelImage);
  }
}
