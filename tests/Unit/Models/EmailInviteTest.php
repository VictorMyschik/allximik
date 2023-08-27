<?php

namespace Models;

use App\Models\EmailInvite;
use App\Models\Hike;
use App\Models\HikeType;
use App\Models\Reference\Country;
use App\Models\User;
use Tests\BaseTest;
use Tests\Helpers\RawDataHelper;

class EmailInviteTest extends BaseTest
{
  public function testUIH()
  {
    $this->be(User::findOrFail(1));

    $hike = new Hike();
    $hike->setName(RawDataHelper::getName());
    $hike->setUserID(1);
    $hike->setStatus(Hike::STATUS_ACTIVE);
    $hike->setCountryID(self::randomIdFromClass(Country::class));
    $hike->setHikeTypeID(self::randomIdFromClass(HikeType::class));
    $hikeID = $hike->save_mr();

    $invite = new EmailInvite();
    $userID = self::randomIdFromClass(User::class);
    $invite->setUserID($userID);
    $invite->setHikeID($hikeID);
    $email = self::randomEmail('test-email');
    $invite->setEmail($email);
    $token = $invite->generateToken();
    $invite->setToken($token);
    $invite->setStatus(EmailInvite::STATUS_NEW);
    $uihID = $invite->save_mr();


    // Asserts
    $invite = EmailInvite::loadBy($uihID);
    self::assertNotNull($invite);
    self::assertEquals($hikeID, $invite->getHike()->id());
    self::assertEquals($userID, $invite->getUser()->id);
    self::assertEquals($email, $invite->getEmail());
    self::assertEquals($token, $invite->getToken());
    self::assertEquals(EmailInvite::STATUS_NEW, $invite->getStatus());

    // Update
    $email = self::randomEmail('test-email');
    $invite->setEmail($email);
    $invite->setStatus(EmailInvite::STATUS_SEND);

    $uihID = $invite->save_mr();

    // Asserts
    $invite = EmailInvite::loadBy($uihID);
    self::assertNotNull($invite);
    self::assertEquals($email, $invite->getEmail());
    self::assertEquals(EmailInvite::STATUS_SEND, $invite->getStatus());

    // Delete
    //$invite->delete_mr();
    //$invite = EmailInvite::loadBy($uihID);
    //self::assertNull($invite);
  }
}
