<?php

namespace Tests;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BaseTest extends TestCase
{

  public const TEST_CLIENT_NAME = 'TEST API CLIENT';

  protected ?int $testApiClientID;

  public function setUp(): void
  {
    parent::setUp();
  }

  public function tearDown(): void
  {
  }

  protected static function randomIdFromClass(string $className): ?int
  {
    $object = new $className();
    $list = DB::table($object::getTableName())->limit(1000)->pluck('id')->toArray();

    return empty($list) ? null : $list[array_rand($list)];
  }

  protected static function randomString(?int $length = null, bool $upper = false): string
  {
    $length = $length ?: 50;

    $string = Str::random($length);

    if ($upper) {
      return mb_strtoupper($string);
    }

    return $string;
  }

  protected static function randomFloat(): float
  {
    return rand(1, 999) . '.' . rand(1, 9) . rand(1, 9);
  }
}
