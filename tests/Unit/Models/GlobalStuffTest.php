<?php

namespace Models;

use App\Models\GlobalCategoryStuff;
use App\Models\GlobalStuff;
use Tests\BaseTest;

class GlobalStuffTest extends BaseTest
{
  public function testGlobalStuff()
  {
    $this->createCategory();

    $globalStuff = new GlobalStuff();

    $name = self::randomString(255);
    $globalStuff->setName($name);
    $description = self::randomString(8000);
    $globalStuff->setDescription($description);
    $categoryID = self::randomIdFromClass(GlobalCategoryStuff::class);
    $globalStuff->setCategoryID($categoryID);

    $globalStuffID = $globalStuff->save_mr();

    // Asserts
    $globalStuff = GlobalStuff::loadBy($globalStuffID);
    self::assertNotNull($globalStuff);
    self::assertEquals($name, $globalStuff->getName());
    self::assertEquals($description, $globalStuff->getDescription());
    self::assertEquals($categoryID, $globalStuff->getCategory()->id());

    // Update
    $name = self::randomString(255);
    $globalStuff->setName($name);
    $description = self::randomString(8000);
    $globalStuff->setDescription($description);
    $categoryID = self::randomIdFromClass(GlobalCategoryStuff::class);
    $globalStuff->setCategoryID($categoryID);

    $globalStuffID = $globalStuff->save_mr();

    // Asserts
    $globalStuff = GlobalStuff::loadBy($globalStuffID);
    self::assertNotNull($globalStuff);
    self::assertEquals($name, $globalStuff->getName());
    self::assertEquals($description, $globalStuff->getDescription());
    self::assertEquals($categoryID, $globalStuff->getCategory()->id());

    // Set null
    $globalStuff->setDescription(null);
    $globalStuff->setCategoryID(null);
    $globalStuffID = $globalStuff->save_mr();

    // Asserts
    $globalStuff = GlobalStuff::loadBy($globalStuffID);
    self::assertNotNull($globalStuff);
    self::assertNull($globalStuff->getDescription());
    self::assertNull($globalStuff->getCategory());


    // Delete
    $globalStuff->delete_mr();
    $globalStuff = GlobalStuff::loadBy($globalStuffID);
    self::assertNull($globalStuff);
  }

  private function createCategory(): void
  {
    $globalCategoryStuff = new GlobalCategoryStuff();

    $name = self::randomString(255);
    $globalCategoryStuff->setName($name);
    $description = self::randomString(8000);
    $globalCategoryStuff->setDescription($description);

    $globalCategoryStuff->save_mr();
  }
}
