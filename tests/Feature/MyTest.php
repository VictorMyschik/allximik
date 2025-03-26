<?php

namespace Tests\Feature;

use App\Services\ImportService;
use App\Services\ImportServiceInterface;
use App\Services\OfferRepositoryInterface;
use App\Services\ParsingService\Enum\SiteType;
use App\Services\ParsingService\LinkRepositoryInterface;
use App\Services\ParsingService\RunnerService;
use App\Services\Telegram\ClientInterface;
use App\Services\Telegram\TelegramService;
use Psr\Log\LoggerInterface;
use Tests\TestCase;

class MyTest extends TestCase
{
    public function testMy(): void
    {
        $service = new TelegramService(
            client: app(ClientInterface::class),
            offerRepository: app(OfferRepositoryInterface::class),
            linkRepository: app(LinkRepositoryInterface::class),
            importService: app(ImportServiceInterface::class)
        );

        $url = 'https://www.olx.pl/nieruchomosci/mieszkania/sprzedaz/warszawa/?search%5Bfilter_float_price:to%5D=800000&search%5Bfilter_float_m:from%5D=60&search%5Bfilter_enum_rooms%5D%5B0%5D=three';
        $service->manageBot('488545536', $url);
    }

    public function testImport(): void
    {
        $service = new ImportService(
            linkRepository: app(LinkRepositoryInterface::class),
            logger: app(LoggerInterface::class),
        );

        $url = 'https://www.olx.pl/nieruchomosci/mieszkania/sprzedaz/warszawa/?search%5Bfilter_enum_rooms%5D%5B0%5D=three&search%5Bfilter_float_m%3Afrom%5D=60&search%5Bfilter_float_price%3Ato%5D=800000';
        $service->import($url, '488545536');
    }

    public function testRunner(): void
    {
        /** @var RunnerService $service */
        $service = app(RunnerService::class);
        $service->parseOffersByLink(1);
    }

    public function testSendMessage(): void
    {
        /** @var TelegramService $service */
        $service = app(TelegramService::class);
        $service->sendMessage(5, SiteType::OLX, ['488545536']);
    }
}
