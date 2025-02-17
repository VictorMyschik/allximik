<?php

namespace Tests\Feature\Models\References;

use App\Models\Reference\Currency;
use App\Models\Reference\CurrencyRate;
use Tests\TestBase;

class CurrencyRateTest extends TestBase
{
    public function testCurrencyRate(): void
    {
        /**
         * 'CurrencyID',
         * 'Rate',
         * //'WriteDate',
         */
        $rate = new CurrencyRate();
        // CurrencyID
        $CurrencyID = self::randomIdFromClass(Currency::class);
        $rate->setCurrencyID($CurrencyID);
        // Rate
        $Rate = rand(1, 99) . '.' . rand(1, 99);
        $rate->setRate($Rate);

        $rate_id = $rate->save_mr();

        /// Asserts
        $rate = CurrencyRate::loadBy($rate_id);
        self::assertNotNull($rate);
        self::assertEquals($CurrencyID, $rate->getCurrency()->id());
        self::assertEquals($Rate, $rate->getRate());

        /// Update
        // CurrencyID
        $CurrencyID = self::randomIdFromClass(Currency::class);
        $rate->setCurrencyID($CurrencyID);
        $Rate = rand(1, 99) . '.' . rand(1, 99);
        $rate->setRate($Rate);

        $rate_id = $rate->save_mr();

        /// Asserts
        $rate = CurrencyRate::loadBy($rate_id);
        self::assertNotNull($rate);
        self::assertEquals($CurrencyID, $rate->getCurrency()->id());
        self::assertEquals($Rate, $rate->getRate());

        /// Delete
        $rate->delete_mr();
        $rate = CurrencyRate::loadBy($rate_id);
        self::assertNull($rate);
    }
}
