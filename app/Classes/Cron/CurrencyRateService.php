<?php

namespace App\Classes\Cron;

use App\Models\Reference\Currency;
use App\Models\Reference\CurrencyRate;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CurrencyRateService
{
  private const URL = 'https://api.currencyapi.com/v3/latest';

  public function __construct(private readonly Client $client) {}

  public function update(): void
  {
    $result = $this->sendRequest();

    $this->saveResult($result);
  }

  private function saveResult(array $data): void
  {
    $rows = [];
    foreach ($data['data'] as $code => $item) {
      $currencyId = DB::table(Currency::getTableName())->where('text_code', $code)->value('id');

      if (!$currencyId) {
        continue;
      }

      $rows[] = ['currency_id' => $currencyId, 'rate' => $item['value']];
    }

    DB::table(CurrencyRate::getTableName())->insert($rows);
  }

  private function sendRequest(): array
  {
    $url = http_build_query([
      'apikey'     => env('CURRENCY_API_KEY'),
      'currencies' => implode(',', Currency::$base_currency)
    ]);

    try {
      $result = $this->client->get(self::URL . '?' . $url);

      return json_decode($result->getBody()->getContents(), true);
    } catch (Exception $e) {
      Log::error($e->getMessage());
      throw new Exception($e->getMessage(), $e->getCode());
    }
  }
}
