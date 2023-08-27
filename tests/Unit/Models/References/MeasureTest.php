<?php

namespace Models\References;

use App\Models\Reference\MrMeasure;
use Tests\BaseTest;

class MeasureTest extends BaseTest
{
  public function testMeasure(): void
  {
    /**
     * 'Code',
     * 'TextCode',
     * 'Name',
     */
    $measure = new MrMeasure();
    // Code
    $Code = self::randomString(2);
    $measure->setCode($Code);
    // TextCode
    $TextCode = self::randomString(3);
    $measure->setTextCode($TextCode);
    // Name
    $Name = self::randomString();
    $measure->setName($Name);
    $measure_id = $measure->save_mr();

    /// Asserts
    $measure = MrMeasure::loadBy($measure_id);
    self::assertNotNull($measure);

    self::assertEquals($Code, $measure->getCode());
    self::assertEquals($TextCode, $measure->getTextCode());
    self::assertEquals($Name, $measure->getName());

    /// Update
    // Code
    $Code = self::randomString(2);
    $measure->setCode($Code);
    // TextCode
    $TextCode = self::randomString(3);
    $measure->setTextCode($TextCode);
    // Name
    $Name = self::randomString();
    $measure->setName($Name);
    $measure_id = $measure->save_mr();

    /// Asserts
    $measure = MrMeasure::loadBy($measure_id);
    self::assertNotNull($measure);

    self::assertEquals($Code, $measure->getCode());
    self::assertEquals($TextCode, $measure->getTextCode());
    self::assertEquals($Name, $measure->getName());

    /// Delete
    $measure->delete_mr();
    $measure = MrMeasure::loadBy($measure_id);
    self::assertNull($measure);
  }
}
