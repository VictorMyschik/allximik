<?php

namespace Models;

use App\Models\GlobalCategoryStuff;
use Tests\BaseTest;

class GlobalCategoryStuffTest extends BaseTest
{
  public function testGlobalCategoryStuff()
  {
    $globalCategoryStuff = new GlobalCategoryStuff();

    $name = self::randomString(255);
    $globalCategoryStuff->setName($name);
    $description = self::randomString(8000);
    $globalCategoryStuff->setDescription($description);

    $globalCategoryStuffID = $globalCategoryStuff->save_mr();


    // Asserts
    $globalCategoryStuff = GlobalCategoryStuff::loadBy($globalCategoryStuffID);
    self::assertNotNull($globalCategoryStuff);
    self::assertEquals($name, $globalCategoryStuff->getName());
    self::assertEquals($description, $globalCategoryStuff->getDescription());


    // Update
    $name = self::randomString(255);
    $globalCategoryStuff->setName($name);
    $description = self::randomString(8000);
    $globalCategoryStuff->setDescription($description);

    $globalCategoryStuffID = $globalCategoryStuff->save_mr();


    // Asserts
    $globalCategoryStuff = GlobalCategoryStuff::loadBy($globalCategoryStuffID);
    self::assertNotNull($globalCategoryStuff);
    self::assertEquals($name, $globalCategoryStuff->getName());
    self::assertEquals($description, $globalCategoryStuff->getDescription());
  }

  public function testParent()
  {
    $parentGlobalCategoryStuff = new GlobalCategoryStuff();

    $parentName = self::randomString(255);
    $parentGlobalCategoryStuff->setName($parentName);
    $parentDescription = self::randomString(8000);
    $parentGlobalCategoryStuff->setDescription($parentDescription);

    $parentGlobalCategoryStuffID = $parentGlobalCategoryStuff->save_mr();


    // Child
    $globalCategoryStuff = new GlobalCategoryStuff();

    $name = self::randomString(255);
    $globalCategoryStuff->setName($name);
    $description = self::randomString(8000);
    $globalCategoryStuff->setDescription($description);

    $globalCategoryStuff->setParentID($parentGlobalCategoryStuffID);

    $globalCategoryStuffID = $globalCategoryStuff->save_mr();

    // Asserts
    $globalCategoryStuff = GlobalCategoryStuff::loadBy($globalCategoryStuffID);
    self::assertNotNull($globalCategoryStuff);
    self::assertEquals($parentName, $globalCategoryStuff->getParent()->getName());

    $parentGlobalCategoryStuff->delete_mr();

    // Check delete cascade
    $globalCategoryStuff = GlobalCategoryStuff::loadBy($globalCategoryStuffID);
    self::assertNull($globalCategoryStuff);
  }
}
