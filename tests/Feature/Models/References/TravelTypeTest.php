<?php

namespace Tests\Feature\Models\References;

use App\Models\TravelType;
use Tests\TestBase;

class TravelTypeTest extends TestBase
{
    public function testTravelType()
    {
        $travelType = new TravelType();

        $name = self::randomString(50);
        $travelType->setName($name);

        $description = self::randomString(8000);
        $travelType->setDescription($description);

        $travelTypeID = $travelType->save_mr();

        // Asserts
        $travelType = TravelType::loadBy($travelTypeID);
        self::assertNotNull($travelType);
        $this->assertEquals($name, $travelType->getName());
        $this->assertEquals($description, $travelType->getDescription());

        // Update
        $name = self::randomString(50);
        $travelType->setName($name);

        $description = self::randomString(8000);
        $travelType->setDescription($description);

        $travelTypeID = $travelType->save_mr();

        // Asserts
        $travelType = TravelType::loadBy($travelTypeID);
        self::assertNotNull($travelType);
        $this->assertEquals($name, $travelType->getName());
        $this->assertEquals($description, $travelType->getDescription());

        // Set null
        $travelType->setDescription(null);
        $travelTypeID = $travelType->save_mr();

        // Asserts
        $travelType = TravelType::loadBy($travelTypeID);
        self::assertNotNull($travelType);
        $this->assertEquals($name, $travelType->getName());
        $this->assertNull($travelType->getDescription());

        // Delete
        $travelType->delete_mr();
        $travelType = TravelType::loadBy($travelTypeID);
        self::assertNull($travelType);
    }
}
