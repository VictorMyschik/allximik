<?php

namespace Tests\Helpers;

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
}
