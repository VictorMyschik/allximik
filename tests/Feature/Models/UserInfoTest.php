<?php

namespace Tests\Feature\Models;

use App\Models\User;
use App\Models\UserInfo;
use Carbon\Carbon;
use Tests\TestBase;

class UserInfoTest extends TestBase
{
  public function testUserINfo(): void
  {
    /**
     * user_id
     * full_name
     * gender
     * birthday
     * about
     */

    $userInfo = new UserInfo();

    $userID = self::randomIdFromClass(User::class);
    $userInfo->setUserID($userID);

    $fullName = self::randomString(100);
    $userInfo->setFullName($fullName);

    $gender = rand(0, 1);
    $userInfo->setGender($gender);

    $birthday = Carbon::now()->subYears(rand(18, 100))->format('Y-m-d');
    $userInfo->setBirthday($birthday);

    $about = self::randomString(8000);
    $userInfo->setAbout($about);

    $userInfoID = $userInfo->save_mr();


    // Asserts
    $userInfo = UserInfo::loadBy($userInfoID);
    self::assertNotNull($userInfo);
    self::assertEquals($userID, $userInfo->getUser()->id());
    self::assertEquals($fullName, $userInfo->getFullName());
    self::assertEquals($gender, $userInfo->getGender());
    self::assertEquals($birthday, $userInfo->getBirthday());
    self::assertEquals($birthday, $userInfo->getBirthdayObject()->format('Y-m-d'));
    self::assertEquals($about, $userInfo->getAbout());


    // Update

    $userID = self::randomIdFromClass(User::class);
    $userInfo->setUserID($userID);

    $fullName = self::randomString(100);
    $userInfo->setFullName($fullName);

    $gender = rand(0, 1);
    $userInfo->setGender($gender);

    $birthday = Carbon::now()->subYears(rand(18, 100))->format('Y-m-d');
    $userInfo->setBirthday($birthday);

    $about = self::randomString(8000);
    $userInfo->setAbout($about);

    $userInfoID = $userInfo->save_mr();


    // Asserts
    $userInfo = UserInfo::loadBy($userInfoID);
    self::assertNotNull($userInfo);
    self::assertEquals($userID, $userInfo->getUser()->id());
    self::assertEquals($fullName, $userInfo->getFullName());
    self::assertEquals($gender, $userInfo->getGender());
    self::assertEquals($birthday, $userInfo->getBirthday());
    self::assertEquals($birthday, $userInfo->getBirthdayObject()->format('Y-m-d'));
    self::assertEquals($about, $userInfo->getAbout());


    // Set null
    $userInfo->setBirthday(null);
    $userInfo->setAbout(null);

    $userInfoID = $userInfo->save_mr();


    // Asserts
    $userInfo = UserInfo::loadBy($userInfoID);
    self::assertNotNull($userInfo);
    self::assertNull($userInfo->getBirthday());
    self::assertNull($userInfo->getAbout());


    // Delete
    $userInfo->delete_mr();
    $userInfo = UserInfo::loadBy($userInfoID);
    self::assertNull($userInfo);
  }
}
