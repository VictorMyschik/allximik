<?php

namespace Tests\Feature\Models;

use App\Models\CategoryEquipment;
use Tests\TestBase;

class CategoryEquipmentTest extends TestBase
{
    public function testGlobalCategoryStuff()
    {
        $categoryEquipment = new CategoryEquipment();

        $name = self::randomString(255);
        $categoryEquipment->setName($name);
        $description = self::randomString(8000);
        $categoryEquipment->setDescription($description);

        $categoryEquipmentID = $categoryEquipment->save_mr();


        // Asserts
        $categoryEquipment = CategoryEquipment::loadBy($categoryEquipmentID);
        self::assertNotNull($categoryEquipment);
        self::assertEquals($name, $categoryEquipment->getName());
        self::assertEquals($description, $categoryEquipment->getDescription());


        // Update
        $name = self::randomString(255);
        $categoryEquipment->setName($name);
        $description = self::randomString(8000);
        $categoryEquipment->setDescription($description);

        $categoryEquipmentID = $categoryEquipment->save_mr();


        // Asserts
        $categoryEquipment = CategoryEquipment::loadBy($categoryEquipmentID);
        self::assertNotNull($categoryEquipment);
        self::assertEquals($name, $categoryEquipment->getName());
        self::assertEquals($description, $categoryEquipment->getDescription());
    }
}
