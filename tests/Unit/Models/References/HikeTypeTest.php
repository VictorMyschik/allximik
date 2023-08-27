<?php

namespace Models\References;

use App\Models\HikeType;
use Tests\BaseTest;

class HikeTypeTest extends BaseTest
{
  public function testHikeType()
  {
    $hikeType = new HikeType();

    $name = self::randomString(50);
    $hikeType->setName($name);

    $description = self::randomString(8000);
    $hikeType->setDescription($description);

    $hikeTypeID = $hikeType->save_mr();

    // Asserts
    $hikeType = HikeType::loadBy($hikeTypeID);
    self::assertNotNull($hikeType);
    $this->assertEquals($name, $hikeType->getName());
    $this->assertEquals($description, $hikeType->getDescription());

    // Update
    $name = self::randomString(50);
    $hikeType->setName($name);

    $description = self::randomString(8000);
    $hikeType->setDescription($description);

    $hikeTypeID = $hikeType->save_mr();

    // Asserts
    $hikeType = HikeType::loadBy($hikeTypeID);
    self::assertNotNull($hikeType);
    $this->assertEquals($name, $hikeType->getName());
    $this->assertEquals($description, $hikeType->getDescription());

    // Set null
    $hikeType->setDescription(null);
    $hikeTypeID = $hikeType->save_mr();

    // Asserts
    $hikeType = HikeType::loadBy($hikeTypeID);
    self::assertNotNull($hikeType);
    $this->assertEquals($name, $hikeType->getName());
    $this->assertNull($hikeType->getDescription());

    // Delete
    $hikeType->delete_mr();
    $hikeType = HikeType::loadBy($hikeTypeID);
    self::assertNull($hikeType);
  }
}
