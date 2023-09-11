<?php

namespace Models;

use App\Models\Travel;
use App\Models\TravelType;
use App\Models\Reference\Country;
use App\Models\UIH;
use App\Models\User;
use Tests\BaseTest;
use Tests\Helpers\RawDataHelper;

class UIHTest extends BaseTest
{
  public function testUIH()
  {
    $this->be(User::findOrFail(1));

    $name = RawDataHelper::getName();
    User::factory()->create(['name' => $name, 'email' => self::randomEmail($name), 'password' => self::randomString(50)]);

    $travel = new Travel();
    $travel->setName(RawDataHelper::getName());
    $travel->setUserID(1);
    $travel->setStatus(Travel::STATUS_ACTIVE);
    $travel->setCountryID(self::randomIdFromClass(Country::class));
    $travel->setTravelTypeID(self::randomIdFromClass(TravelType::class));
    $travelID = $travel->save_mr();

    $uih = new UIH();
    $userID = self::randomIdFromClass(User::class);
    $uih->setUserID($userID);
    $uih->setTravelID($travelID);
    $uihID = $uih->save_mr();

    // Asserts
    $uih = UIH::loadBy($uihID);
    self::assertNotNull($uih);
    self::assertEquals($travelID, $uih->getTravel()->id());
    self::assertEquals($userID, $uih->getUser()->id);
    self::assertEquals(UIH::STATUS_NEW, $uih->getStatus());

    // Update
    $uih->setStatus(UIH::STATUS_APPROVED);

    $uihID = $uih->save_mr();

    // Asserts
    $uih = UIH::loadBy($uihID);
    self::assertNotNull($uih);
    self::assertEquals(UIH::STATUS_APPROVED, $uih->getStatus());

    // Delete
    $uih->delete_mr();
    $uih = UIH::loadBy($uihID);
    self::assertNull($uih);
  }
}
