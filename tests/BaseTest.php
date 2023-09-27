<?php

namespace Tests;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Helpers\AuthTrait;
use Tests\Helpers\CreateModelsTrait;
use Tests\Helpers\HTTPClientTrait;
use Tests\Helpers\RawDataHelper;

class BaseTest extends TestCase
{
  use CreateModelsTrait;
  use HTTPClientTrait;
  use AuthTrait;

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

  protected static function randomEmail(string $name = null): string
  {
    $name = strlen($name) ? $name : RawDataHelper::getName();

    return strtolower($name . rand(1970, 2020) . '@exmpl.com');
  }
}
