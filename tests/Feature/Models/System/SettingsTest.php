<?php

namespace Tests\Feature\Models\System;

use App\Models\System\Settings;
use Tests\TestBase;

class SettingsTest extends TestBase
{
    public function testSettings(): void
    {
        /**
         * 'active',
         * 'category',
         * 'name',
         * 'code_key',
         * 'value',
         * 'description',
         */
        $setup = new Settings();
        // active
        $setup->setActive(true);
        // category
        $category = self::randomString(50);
        $setup->setCategory($category);
        // name
        $name = self::randomString(255);
        $setup->setName($name);
        // codeKey
        $codeKey = self::randomString(25);
        $setup->setCodeKey($codeKey);
        // codeKey
        $value = self::randomString(255);
        $setup->setValue($value);
        // codeKey
        $description = self::randomString(255);
        $setup->setDescription($description);

        $setupId = $setup->save_mr();

        /// Asserts
        $setup = Settings::loadBy($setupId);
        self::assertNotNull($setup);
        self::assertEquals($category, $setup->getCategory());
        self::assertEquals($name, $setup->getName());
        self::assertEquals($codeKey, $setup->getCodeKey());
        self::assertEquals($value, $setup->getValue());
        self::assertEquals($description, $setup->getDescription());

        /// Update
        // active
        $setup->setActive(false);
        // category
        $category = self::randomString(50);
        $setup->setCategory($category);
        // name
        $name = self::randomString(255);
        $setup->setName($name);
        // codeKey
        $codeKey = self::randomString(25);
        $setup->setCodeKey($codeKey);
        // codeKey
        $value = self::randomString(255);
        $setup->setValue($value);
        // codeKey
        $description = self::randomString(255);
        $setup->setDescription($description);

        $setupId = $setup->save_mr();

        /// Asserts
        $setup = Settings::loadBy($setupId);
        self::assertNotNull($setup);
        self::assertEquals($category, $setup->getCategory());
        self::assertEquals($name, $setup->getName());
        self::assertEquals($codeKey, $setup->getCodeKey());
        self::assertEquals($value, $setup->getValue());
        self::assertEquals($description, $setup->getDescription());

        /// Set null
        $setup->setDescription(null);
        $setup->setCategory(null);

        $setupId = $setup->save_mr();

        /// Asserts
        $setup = Settings::loadBy($setupId);
        self::assertNotNull($setup);
        self::assertNull($setup->getCategory());
        self::assertNull($setup->getDescription());

        /// Delete
        $setup->delete_mr();

        $setup = Settings::loadBy($setupId);
        self::assertNull($setup);
    }
}
