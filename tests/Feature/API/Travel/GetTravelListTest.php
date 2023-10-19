<?php

namespace API\Travel;

use App\Classes\Travel\TravelClass;
use App\Models\Reference\Country;
use App\Models\Travel;
use App\Models\TravelType;
use Illuminate\Support\Facades\Auth;
use Tests\BaseTest;

class GetTravelListTest extends BaseTest
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

  public function testGetPublicList(): void
  {
    // Guest
    foreach ($this->users as $user) {
      $travel = new TravelClass(null);

      foreach ($travel->getPublicList() as $item) {
        self::assertTrue($item->canView($user));
      }

      $list = $travel->getConvertedList();
      foreach ($list as $travelConverted) {
        self::assertEquals(Travel::VISIBLE_KIND_PUBLIC, $travelConverted['visible_kind']['key']);
        self::assertTrue(in_array($travelConverted['status']['key'], [Travel::STATUS_ACTIVE, Travel::STATUS_ARCHIVED]), 'Visible wrong: ' . $travelConverted['status']['name']);
      }
    }

    // Authorized user
    foreach ($this->users as $user) {
      // Auth
      $travel = new TravelClass($user);

      foreach ($travel->getPublicList() as $item) {
        self::assertTrue($item->canView($user));
      }

      $list = $travel->getConvertedList();
      foreach ($list as $travelConverted) {
        self::assertTrue(in_array($travelConverted['visible_kind']['key'], [Travel::VISIBLE_KIND_PUBLIC, Travel::VISIBLE_KIND_PLATFORM]));
        self::assertTrue(in_array($travelConverted['status']['key'], [Travel::STATUS_ACTIVE, Travel::STATUS_ARCHIVED]), 'Visible wrong: ' . $travelConverted['status']['name']);
      }
    }
  }

  public function testGetPersonalList(): void
  {
    foreach ($this->users as $user) {
      $travel = new TravelClass($user);

      foreach ($travel->getPersonalList() as $item) {
        self::assertTrue($item->canView($user));
        self::assertEquals($item->getUser()->id(), $user->id());
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
      $password = 'test_password';

      $users[] = $this->createUser($email, $password);
    }

    $this->users = $users;

    $cnt = 50;
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
