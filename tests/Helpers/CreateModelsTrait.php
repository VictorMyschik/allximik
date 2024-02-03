<?php

namespace Tests\Helpers;

use App\Models\Travel;
use App\Models\Reference\Country;
use App\Models\TravelType;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

trait CreateModelsTrait
{
  public static function createUser($email, $password): User
  {
    $user = new User();
    $user->name = self::randomString(20);
    $user->email = $email;
    $user->password = Hash::make($password);
    $user->save();

    return $user;
  }

  public function createTravel(int $userID, array $data = []): Travel
  {
    $travel = new Travel();
    $travel->setName(self::randomString(20));
    $travel->setUserID($userID);
    $travel->setCountryID(self::randomIdFromClass(Country::class));
    $travel->setVisibleKind(Travel::VISIBLE_KIND_PUBLIC);
    $travel->setStatus(Travel::STATUS_ACTIVE);
    $travel->setTravelTypeID(self::randomIdFromClass(TravelType::class));

    // reset other fields
    $travel->fill($data);

    $travel->save_mr();

    return $travel;
  }
}
