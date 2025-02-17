<?php

namespace Cron;

use App\Classes\Cron\CurrencyRateService;
use App\Models\Reference\Currency;
use App\Models\Reference\CurrencyRate;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\DB;
use Tests\TestBase;

class CurrencyRateServiceTest extends TestBase
{
    public function testUpdate(): void
    {
        $expectedResult = [
            'meta' => [
                'last_updated_at' => '2024-02-02T23:59:59Z',
            ],
            'data' => [
                'BYN' => ['code' => 'BYN', 'value' => 3.2724992557],
                'EUR' => ['code' => 'EUR', 'value' => 0.9269801853],
                'RUB' => ['code' => 'RUB', 'value' => 90.9992752656],
                'USD' => ['code' => 'USD', 'value' => 1],
            ],
        ];

        $mock = new MockHandler([
            new Response(200, [], json_encode($expectedResult)),
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $service = new CurrencyRateService($client);
        $service->update();

        // Asserts
        foreach ($expectedResult['data'] as $code => $item) {
            $currencyId = DB::table(Currency::getTableName())->where('text_code', $code)->value('id');

            $value = DB::table(CurrencyRate::getTableName())
                ->where('currency_id', $currencyId)
                ->latest()
                ->value('rate');

            self::assertEquals(round($item['value'], 4), $value);
        }
    }
}
