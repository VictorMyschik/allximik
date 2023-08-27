<?php

namespace Models\References;

use App\Models\Reference\Currency;
use App\Models\Reference\MrCurrencyRate;
use Tests\BaseTest;

class CurrencyRateTest extends BaseTest
{
  public function testCurrencyRate(): void
  {
    /**
     * 'CurrencyID',
     * 'Scale',
     * 'Rate',
     * //'WriteDate',
     */
    $rate = new MrCurrencyRate();
    // CurrencyID
    $CurrencyID = self::randomIdFromClass(Currency::class);
    $rate->setCurrencyID($CurrencyID);
    // Scale
    $Scale = rand(1, 3);
    $rate->setScale($Scale);
    // Rate
    $Rate = rand(1, 99);
    $rate->setRate($Rate);

    $rate_id = $rate->save_mr();

    /// Asserts
    $rate = MrCurrencyRate::loadBy($rate_id);
    self::assertNotNull($rate);
    self::assertEquals($CurrencyID, $rate->getCurrency()->id());
    self::assertEquals($Scale, $rate->getScale());
    self::assertEquals($Rate, $rate->getRate());

    /// Update
    // CurrencyID
    $CurrencyID = self::randomIdFromClass(Currency::class);
    $rate->setCurrencyID($CurrencyID);
    // Scale
    $Scale = rand(1, 3);
    $rate->setScale($Scale);
    // Rate
    $Rate = rand(1, 99);
    $rate->setRate($Rate);

    $rate_id = $rate->save_mr();

    /// Asserts
    $rate = MrCurrencyRate::loadBy($rate_id);
    self::assertNotNull($rate);
    self::assertEquals($CurrencyID, $rate->getCurrency()->id());
    self::assertEquals($Scale, $rate->getScale());
    self::assertEquals($Rate, $rate->getRate());

    /// Delete
    $rate->delete_mr();
    $rate = MrCurrencyRate::loadBy($rate_id);
    self::assertNull($rate);
  }
}
