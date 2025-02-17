<?php

namespace Tests\Feature\Models;

use App\Models\CategoryEquipment;
use App\Models\Equipment;
use Tests\TestBase;

class EquipmentTest extends TestBase
{
    public function testGlobalStuff()
    {
        $this->createCategory();

        $equipment = new Equipment();

        $name = self::randomString(255);
        $equipment->setName($name);
        $description = self::randomString(8000);
        $equipment->setDescription($description);
        $categoryID = self::randomIdFromClass(CategoryEquipment::class);
        $equipment->setCategoryID($categoryID);

        $globalStuffID = $equipment->save_mr();

        // Asserts
        $equipment = Equipment::loadBy($globalStuffID);
        self::assertNotNull($equipment);
        self::assertEquals($name, $equipment->getName());
        self::assertEquals($description, $equipment->getDescription());
        self::assertEquals($categoryID, $equipment->getCategory()->id());

        // Update
        $name = self::randomString(255);
        $equipment->setName($name);
        $description = self::randomString(8000);
        $equipment->setDescription($description);
        $categoryID = self::randomIdFromClass(CategoryEquipment::class);
        $equipment->setCategoryID($categoryID);

        $globalStuffID = $equipment->save_mr();

        // Asserts
        $equipment = Equipment::loadBy($globalStuffID);
        self::assertNotNull($equipment);
        self::assertEquals($name, $equipment->getName());
        self::assertEquals($description, $equipment->getDescription());
        self::assertEquals($categoryID, $equipment->getCategory()->id());

        // Set null
        $equipment->setDescription(null);
        $equipment->setCategoryID(null);
        $globalStuffID = $equipment->save_mr();

        // Asserts
        $equipment = Equipment::loadBy($globalStuffID);
        self::assertNotNull($equipment);
        self::assertNull($equipment->getDescription());
        self::assertNull($equipment->getCategory());


        // Delete
        $equipment->delete_mr();
        $equipment = Equipment::loadBy($globalStuffID);
        self::assertNull($equipment);
    }

    private function createCategory(): void
    {
        $globalCategoryStuff = new CategoryEquipment();

        $name = self::randomString(255);
        $globalCategoryStuff->setName($name);
        $description = self::randomString(8000);
        $globalCategoryStuff->setDescription($description);

        $globalCategoryStuff->save_mr();
    }
}
