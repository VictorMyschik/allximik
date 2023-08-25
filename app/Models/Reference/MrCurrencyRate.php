<?php

namespace App\Models\Reference;

use App\Classes\API\APIServiceBase;
use App\Models\Lego\Traits\Fields\MrWriteDateFieldTrait;
use App\Models\Lego\Traits\Other\MrDateTimeHelperTrait;
use App\Models\ORM\ORM;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MrCurrencyRate extends ORM
{
  use MrDateTimeHelperTrait;

  protected $table = 'mr_currency_rate';
  protected $fillable = array(
    'CurrencyID',
    'Scale',
    'Rate',
    //'WriteDate',
  );

  use MrWriteDateFieldTrait;

  public function getCurrency(): MrCurrency
  {
    return MrCurrency::loadByOrDie($this->CurrencyID);
  }

  public function setCurrencyID(int $value): void
  {
    $this->CurrencyID = $value;
  }

  /**
   * Количество единиц валюты
   *
   * @return int
   */
  public function getScale(): int
  {
    return $this->Scale;
  }

  public function setScale(int $value): void
  {
    $this->Scale = $value;
  }

  public function getRate(): float
  {
    return $this->Rate;
  }

  public function setRate(float $value): void
  {
    $this->Rate = $value;
  }

  /**
   * Обновление курсов валют
   */
  public static function UpdateCurrencyRates(): void
  {
    $json_data = APIServiceBase::loadUrlData('https://www.nbrb.by/api/exrates/rates?periodicity=0', false);
    $data = @json_decode($json_data, true);

    foreach($data as $item) {
      if(!in_array($item['Cur_Abbreviation'], MrCurrency::$base_currency)) {
        continue;
      }

      $currencyId = MrCurrency::where('TextCode', $item['Cur_Abbreviation'])->value('id');

      if(!$currencyId) {
        $log_text = "Валюта по коду ({$item['Cur_Abbreviation']}) не найдена в справочнике валют";
        Log::alert($log_text);
      }

      // Обновление сегодняшних курсов или создание новой записи (если не найдена)
      DB::table(MrCurrencyRate::getTableName())
        ->where('Scale', $item['Cur_Scale'])
        ->where('CurrencyID', $currencyId)
        ->where('Rate', $item['Cur_OfficialRate'])
        ->updateOrInsert([
          'CurrencyID' => $currencyId,
          'Scale'      => $item['Cur_Scale'],
          'Rate'       => $item['Cur_OfficialRate'],
        ]);
    }
  }
}
