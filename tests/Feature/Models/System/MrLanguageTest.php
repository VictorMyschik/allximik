<?php

namespace Tests\Feature\Models\System;

use App\Models\System\Language;
use Tests\TestBase;

class MrLanguageTest extends TestBase
{
    /**
     * 'Code',
     * 'Name',
     * 'Description',
     */
    public function testLanguage(): void
    {
        $language = new Language();
        // 'Code',
        $code = $this->randomString(2, true);
        $language->setCode($code);
        // 'Name',
        $name = $this->randomString(50);
        $language->setName($name);

        $languageId = $language->save_mr();
        $language = null;
        self::assertNotNull($languageId);


        /// Asserts
        $language = Language::loadBy($languageId);
        self::assertNotNull($language);
        self::assertEquals($code, $language->getCode());
        self::assertEquals($name, $language->getName());


        /// Update
        // 'Code',
        $code = $this->randomString(2, true);
        $language->setCode($code);
        // 'Name',
        $name = $this->randomString(50);
        $language->setName($name);

        $languageId = $language->save_mr();
        $language = null;

        /// Asserts
        $language = Language::loadBy($languageId);
        self::assertNotNull($language);
        self::assertEquals($code, $language->getCode());
        self::assertEquals($name, $language->getName());

        /// Delete
        $language->delete_mr();
        self::assertNull(Language::loadBy($languageId));
    }
}
