<?php

namespace Tests\Feature;

use App\Services\ImportService;
use App\Services\Telegram\TelegramService;
use Tests\TestCase;

class MyTest extends TestCase
{
    public function testParse(): void
    {
        /** @var ImportService $service */
        $service = app(ImportService::class);

        $link = 'https://www.olx.pl/praca/informatyka/programista/';
        $service->import($link, '11111');


        /** @var TelegramService $service */
        $service = app(TelegramService::class);
        $service->sendMessage(1, []);
    }
}
