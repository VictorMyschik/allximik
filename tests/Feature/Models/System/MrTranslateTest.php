<?php

namespace Tests\Feature\Models\System;

use App\Models\System\Language;
use App\Models\System\Translate;
use Tests\TestBase;

class MrTranslateTest extends TestBase
{
    public function testTranslate(): void
    {
        $translate = new Translate();

        $languageId = $this->createLanguage();
        $translate->setLanguageID($languageId);
        $code = self::randomString();
        $translate->setCode($code);
        $tr = self::randomString();
        $translate->setTranslate($tr);

        $translateId = $translate->save_mr();


        /// Asserts
        $translate = Translate::loadBy($translateId);
        self::assertNotNull($translate);

        self::assertEquals($code, $translate->getCode());
        self::assertEquals($languageId, $translate->getLanguage()->id());
        self::assertEquals($tr, $translate->getTranslate());


        /// Update
        // Code
        $code = self::randomString();
        $translate->setCode($code);
        // Translate
        $tr = self::randomString();
        $translate->setTranslate($tr);

        $translateId = $translate->save_mr();

        /// Asserts
        $translate = Translate::loadBy($translateId);
        self::assertNotNull($translate);

        self::assertEquals($code, $translate->getCode());
        self::assertEquals($tr, $translate->getTranslate());

        /// Delete
        $translate->delete_mr();
        $translate = Translate::loadBy($translateId);
        self::assertNull($translate);
    }

    private function createLanguage(): int
    {
        $language = new Language();
        $code = $this->randomString(2, true);
        $language->setCode($code);
        $name = $this->randomString(50);
        $language->setName($name);

        $languageId = $language->save_mr();
        $language = null;
        self::assertNotNull($languageId);

        return $languageId;
    }
}
