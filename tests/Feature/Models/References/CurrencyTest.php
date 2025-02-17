<?php

namespace Tests\Feature\Models\References;

use App\Models\Reference\Currency;
use Tests\TestBase;

class CurrencyTest extends TestBase
{
    function testCurrency(): void
    {
        /**
         * 'Code',
         * 'TextCode',
         * 'DateFrom',
         * 'DateTo',
         * 'Name',
         * 'Rounding',
         * 'Description'
         */

        $currency = new Currency();
        //'Code',
        $Code = self::randomString(3);
        $currency->setCode($Code);
        //'TextCode',
        $TextCode = $this->randomString(3);
        $currency->setTextCode($TextCode);
        //'Name',
        $Name = $this->randomString(200);
        $currency->setName($Name);
        //'Rounding',
        $Rounding = rand(1, 5);
        $currency->setRounding($Rounding);
        //'Description'
        $Description = $this->randomString();
        $currency->setDescription($Description);

        $currency_id = $currency->save_mr();


        /// Asserts
        $currency = Currency::loadBy($currency_id);
        self::assertNotNull($currency);
        self::assertEquals($Code, $currency->getCode());
        self::assertEquals($TextCode, $currency->getTextCode());
        self::assertEquals($Name, $currency->getName());
        self::assertEquals($Rounding, $currency->getRounding());
        self::assertEquals($Description, $currency->getDescription());


        /// Update
        //'Code',
        $Code = $this->randomString(3);
        $currency->setCode($Code);
        //'TextCode',
        $TextCode = $this->randomString(3);
        $currency->setTextCode($TextCode);
        //'Name',
        $Name = $this->randomString(200);
        $currency->setName($Name);
        //'Rounding',
        $Rounding = rand(1, 5);
        $currency->setRounding($Rounding);
        //'Description'
        $Description = $this->randomString();
        $currency->setDescription($Description);

        $currency_id = $currency->save_mr();


        /// Asserts
        $currency = Currency::loadBy($currency_id);
        self::assertNotNull($currency);
        self::assertEquals($Code, $currency->getCode());
        self::assertEquals($TextCode, $currency->getTextCode());
        self::assertEquals($Name, $currency->getName());
        self::assertEquals($Rounding, $currency->getRounding());
        self::assertEquals($Description, $currency->getDescription());

        /// NUll
        $currency->setDescription(null);
        $currency_id = $currency->save_mr();


        /// Asserts
        $currency = Currency::loadBy($currency_id);
        self::assertNotNull($currency);
        self::assertNull($currency->getDescription());


        //Delete
        $currency->delete_mr();
        $currency = Currency::loadBy($currency_id);
        self::assertNull($currency);
    }
}
