<?php

namespace Models;

use App\Models\EmailInvite;
use App\Models\Travel;
use App\Models\TravelType;
use App\Models\Reference\Country;
use App\Models\User;
use Tests\BaseTest;
use Tests\Helpers\RawDataHelper;

class EmailInviteTest extends BaseTest
{
  public function testUIH()
  {
    $this->be(User::findOrFail(1));

    $travel = new Travel();
    $travel->setName(RawDataHelper::getName());
    $travel->setUserID(1);
    $travel->setStatus(Travel::STATUS_ACTIVE);
    $travel->setCountryID(self::randomIdFromClass(Country::class));
    $travel->setTravelTypeID(self::randomIdFromClass(TravelType::class));
    $travelID = $travel->save_mr();

    $invite = new EmailInvite();
    $userID = self::randomIdFromClass(User::class);
    $invite->setUserID($userID);
    $invite->setTravelID($travelID);
    $email = self::randomEmail('test-email');
    $invite->setEmail($email);
    $token = $invite->generateToken();
    $invite->setToken($token);
    $invite->setStatus(EmailInvite::STATUS_NEW);
    $uihID = $invite->save_mr();


    // Asserts
    $invite = EmailInvite::loadBy($uihID);
    self::assertNotNull($invite);
    self::assertEquals($travelID, $invite->getTravel()->id());
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
