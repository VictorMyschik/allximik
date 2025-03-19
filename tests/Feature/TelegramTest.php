<?php

namespace Tests\Feature;

use App\Services\OfferRepositoryInterface;
use App\Services\Telegram\ClientInterface;
use App\Services\Telegram\TelegramService;
use Tests\TestCase;

class TelegramTest extends TestCase
{
    public function testMy(): void
    {
        $r = 'https:\u002F\u002Fireland.apollo.olxcdn.com:443\u002Fv1\u002Ffiles\u002Fe403zyl7jn0r-PL\u002Fimage;s=1200x900';

        $src = str_replace('\u002F', '/', $r);
        $src = str_replace('\u002F', '/', $r);


    }

    public function testTelegram(): void
    {
        $service = new TelegramService(
            app(ClientInterface::class),
            app(OfferRepositoryInterface::class),
        );

        $service->sendMessage(1);
    }
}
