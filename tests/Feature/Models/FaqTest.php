<?php

namespace Tests\Feature\Models;

use App\Models\Faq;
use Tests\TestBase;

class FaqTest extends TestBase
{
    public function testFaq()
    {
        $faq = new Faq();
        $title = self::randomString(255);
        $faq->setTitle($title);
        $text = self::randomString(8000);
        $faq->setText($text);
        $faq->setActive(true);

        $faqID = $faq->save_mr();


        // Asserts
        $faq = Faq::loadBy($faqID);
        self::assertNotNull($faq);
        self::assertTrue($faq->isActive());
        self::assertEquals($title, $faq->getTitle());
        self::assertEquals($text, $faq->getText());

        // Update
        $title = self::randomString(255);
        $faq->setTitle($title);
        $text = self::randomString(8000);
        $faq->setText($text);
        $faq->setActive(false);

        $faqID = $faq->save_mr();


        // Asserts
        $faq = Faq::loadBy($faqID);
        self::assertNotNull($faq);
        self::assertFalse($faq->isActive());
        self::assertEquals($title, $faq->getTitle());
        self::assertEquals($text, $faq->getText());


        // Delete
        $faq->delete_mr();
        $faq = Faq::loadBy($faqID);
        self::assertNull($faq);
    }
}
