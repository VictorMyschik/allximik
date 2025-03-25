<?php

namespace Tests\Feature;

use App\Services\ImportService;
use App\Services\ImportServiceInterface;
use App\Services\OfferRepositoryInterface;
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

        $url = 'https://realting.com/ru/poland/warsaw/apartments/3-bedrooms?page=2&movemap-input=1&slug=property-for-sale&type=apartments&Estate%5Bform_type%5D=apartments&Estate%5Broom_alias%5D=3-bedrooms&geoArray=208350%2C208351%2C76415%2C208352%2C115130&search=%D0%92%D0%B0%D1%80%D1%88%D0%B0%D0%B2%D0%B0&Estate%5Bgeo_id%5D=208350&Estate%5Bcurrency%5D=USD&Estate%5BminArea%5D=60&Estate%5BroomCnt%5D%5B3%5D=3&Estate%5Bzoom%5D=11&Estate%5Bx1%5D=20.63507&Estate%5By1%5D=52.00179&Estate%5Bx2%5D=21.37871&Estate%5By2%5D=52.46061&referrer_id=';
        $service->import($url, '488545536');
    }

    public function testRunner(): void
    {
        /** @var RunnerService $service */
        $service = app(RunnerService::class);
        $service->parseOffersByLink(2);
    }

    public function testSendMessage(): void
    {
        /** @var TelegramService $service */
        $service = app(TelegramService::class);
        $service->sendMessage(519, ['488545536']);
    }
}
