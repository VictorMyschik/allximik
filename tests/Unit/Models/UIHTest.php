<?php

namespace Models;

use App\Models\Hike;
use App\Models\HikeType;
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

    $hike = new Hike();
    $hike->setName(RawDataHelper::getName());
    $hike->setUserID(1);
    $hike->setStatus(Hike::STATUS_ACTIVE);
    $hike->setCountryID(self::randomIdFromClass(Country::class));
    $hike->setHikeTypeID(self::randomIdFromClass(HikeType::class));
    $hikeID = $hike->save_mr();

    $uih = new UIH();
    $userID = self::randomIdFromClass(User::class);
    $uih->setUserID($userID);
    $uih->setHikeID($hikeID);
    $uihID = $uih->save_mr();

    // Asserts
    $uih = UIH::loadBy($uihID);
    self::assertNotNull($uih);
    self::assertEquals($hikeID, $uih->getHike()->id());
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
