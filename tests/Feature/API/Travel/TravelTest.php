<?php

namespace API\Travel;

use App\Classes\Travel\TravelClass;
use App\Models\Reference\Country;
use App\Models\Travel;
use App\Models\TravelType;
use App\Models\User;
use Tests\BaseTest;

class TravelTest extends BaseTest
{
  private array $users;
  private array $travels;

  public function setUp(): void
  {
    parent::setUp();

    $this->travels = $this->generateFakeData(10);
  }

  public function tearDown(): void
  {
    foreach ($this->users as $user) {
      $user->delete();
    }
  }

  public function testGetList(): void
  {
    $user = User::find(1);

    $travel = new TravelClass($user);

    foreach ($travel->getList() as $item) {
      $this->assertTrue($item->canView($user));
    }

    $list = $travel->getConvertedList();
    foreach ($list as $travelConverted) {

    }
  }

  /**
   * Generate public travels with random data.
   * Also, will generate some travels with other visible kinds.
   *
   * @return Travel[]
   */
  private function generateFakeData(): array
  {
    // Generate random users
    for ($i = 0; $i <= 10; $i++) {
      $email = self::randomEmail();
      $password = self::randomString(20);

      $users[] = $this->createUser($email, $password);
    }

    $this->users = $users;

    $cnt = 10;

    for ($i = 0; $i <= $cnt; $i++) {
      $out[] = $this->createTravel($users[array_rand($users)]->id(), [
        'name'           => self::randomString(20),
        'country_id'     => self::randomIdFromClass(Country::class),
        'visible_kind'   => Travel::VISIBLE_KIND_FOR_ME,
        'status'         => Travel::STATUS_ACTIVE,
        'travel_type_id' => self::randomIdFromClass(TravelType::class)
      ]);

    }

    return $out;
  }
}
