<?php

namespace API\Travel;

use App\Models\Reference\Country;
use App\Models\Travel;
use App\Models\TravelType;
use App\Models\User;
use Tests\BaseTest;

class CRUDTravelTest extends BaseTest
{
  public function setUp(): void
  {
    parent::setUp();

    $email = self::randomEmail();
    $this->user = $this->createUser($email, 'Qwerty123!');
    $this->login($email, 'Qwerty123!');
  }

  public function tearDown(): void
  {
    parent::tearDown();

    User::where('email', $this->user->email)->delete();

    self::assertNull(Travel::where('user_id', $this->user->id)->first());
  }

  public function testCreateTravel(): void
  {
    $payload = [
      'name'           => 'Test travel',
      'description'    => 'Test description',
      'country_id'     => self::randomIdFromClass(Country::class),
      'visible_kind'   => array_rand(Travel::getVisibleKindList()),
      'status'         => array_rand(Travel::getStatusList()),
      'travel_type_id' => self::randomIdFromClass(TravelType::class),
    ];

    [$code, $content] = self::doPost(route('api.travel.create'), $payload);
    self::assertEquals(201, $code);
    self::assertTrue($content['result']);
    self::assertNotEmpty($content['content']);

    self::assertEquals($payload['name'], $content['content']['name']);
    self::assertEquals($payload['description'], $content['content']['description']);
    self::assertEquals($payload['country_id'], $content['content']['country']['id']);
    self::assertEquals($payload['status'], $content['content']['status']['key']);
    self::assertEquals($payload['visible_kind'], $content['content']['visible_kind']['key']);
    self::assertEquals($payload['travel_type_id'], $content['content']['travel_type']['id']);


    // Update Travel
    $payload = [
      'id'             => $content['content']['id'],
      'name'           => 'Test travel updates',
      'description'    => 'Test description updates',
      'country_id'     => self::randomIdFromClass(Country::class),
      'visible_kind'   => array_rand(Travel::getVisibleKindList()),
      'status'         => array_rand(Travel::getStatusList()),
      'travel_type_id' => self::randomIdFromClass(TravelType::class),
    ];

    [$code, $content] = self::doPost(route('api.travel.update'), $payload);
    self::assertEquals(200, $code);
    self::assertTrue($content['result']);
    self::assertNotEmpty($content['content']);

    self::assertEquals($payload['name'], $content['content']['name']);
    self::assertEquals($payload['description'], $content['content']['description']);
    self::assertEquals($payload['country_id'], $content['content']['country']['id']);
    self::assertEquals($payload['status'], $content['content']['status']['key']);
    self::assertEquals($payload['visible_kind'], $content['content']['visible_kind']['key']);
    self::assertEquals($payload['travel_type_id'], $content['content']['travel_type']['id']);


    // Delete Travel
    $payload = [
      'id' => $content['content']['id'],
    ];

    [$code, $content] = self::doPost(route('api.travel.delete'), $payload);
    self::assertEquals(200, $code);
    self::assertTrue($content['result']);
    self::assertNull($content['content']);
  }

  public function testCreateMissingTravel(): void
  {
    $payload = [
      'name'           => 'Test travel',
      'description'    => 'Test description',
      'country_id'     => self::randomIdFromClass(Country::class),
      'visible_kind'   => 999, // invalid
      'status'         => array_rand(Travel::getStatusList()),
      'travel_type_id' => self::randomIdFromClass(TravelType::class),
    ];

    [$code, $content] = self::doPost(route('api.travel.create'), $payload);

    self::assertEquals(400, $code);
    self::assertFalse($content['result']);
    self::assertNotEmpty($content['error']);
    self::assertNotEmpty('Input missing', $content['error']);
  }
}
