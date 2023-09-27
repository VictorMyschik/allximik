<?php

namespace API\Travel;

use App\Classes\Travel\TravelClass;
use App\Models\Reference\Country;
use App\Models\Travel;
use App\Models\TravelType;
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

    // Deleted cascade from User
    foreach ($this->travels as $travel) {
      self::assertNull(Travel::loadBy($travel->id()));
    }
  }

  public function testGetList(): void
  {
    foreach ($this->users as $user) {
      $travel = new TravelClass();

      foreach ($travel->getList() as $item) {
        $this->assertTrue($item->canView($user));
      }

      $list = $travel->getConvertedList();
      foreach ($list as $travelConverted) {
        self::assertEquals(Travel::VISIBLE_KIND_PUBLIC, $travelConverted['visible_kind']['key']);
        self::assertEquals(Travel::STATUS_ACTIVE, $travelConverted['status']['key']);
      }
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

    $cnt = 30;
    $out = [];

    for ($i = 0; $i <= $cnt; $i++) {
      $out[] = $this->createTravel($users[array_rand($users)]->id(), [
        'name'           => self::randomString(20),
        'country_id'     => self::randomIdFromClass(Country::class),
        'visible_kind'   => array_rand(Travel::getVisibleKindList()),
        'status'         => array_rand(Travel::getStatusList()),
        'travel_type_id' => self::randomIdFromClass(TravelType::class)
      ]);
    }

    return $out;
  }
}
