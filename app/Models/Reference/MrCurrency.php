<?php

namespace App\Models\Reference;

use App\Helpers\System\MrCacheHelper;
use App\Helpers\System\MrDateTime;
use App\Models\Lego\Traits\Other\MrDateTimeHelperTrait;
use App\Models\ORM\ORM;
use Illuminate\Support\Facades\DB;

class MrCurrency extends ORM
{
  use MrDateTimeHelperTrait;

  protected $table = 'mr_currency';
  protected $fillable = array(
    'Code',
    'TextCode',
    'DateFrom',
    'DateTo',
    'Name',
    'Rounding',
    'Description'
  );

  public static array $base_currency = ['USD', 'BYN', 'RUR', 'EUR', 'CNY'];

  private static int $bynId = 0;

  public static function BYN(): int
  {
    if(self::$bynId)
      return self::$bynId;

    self::$bynId = MrCacheHelper::getCachedData('BYN', function() {
      return MrCurrency::where('TextCode', 'BYN')->value('id');
    });

    return self::$bynId;
  }

  //  Цифровой код
  public function getCode(): string
  {
    return $this->Code;
  }

  public function setCode(string $value): void
  {
    $this->Code = $value;
  }

  public function getTextCode(): string
  {
    return $this->TextCode;
  }

  public function setTextCode(string $value): void
  {
    $this->TextCode = $value;
  }

  public function getDateFrom(): ?MrDateTime
  {
    return $this->getDateNullableField('DateFrom');
  }

  public function setDateFrom(mixed $value): void
  {
    $this->setDateNullableField($value, 'DateFrom');
  }

  public function getDateTo(): ?MrDateTime
  {
    return $this->getDateNullableField('DateTo');
  }

  public function setDateTo(mixed $value): void
  {
    $this->setDateNullableField($value, 'DateTo');
  }

  public function getName(): ?string
  {
    return $this->Name;
  }

  public function setName(string $value): void
  {
    $this->Name = $value;
  }

  public function getRounding(): ?int
  {
    return $this->Rounding;
  }

  public function setRounding(int $value): void
  {
    $this->Rounding = $value;
  }

  public function getDescription(): ?string
  {
    return $this->Description;
  }

  public function setDescription(?string $value): void
  {
    $this->Description = $value;
  }

  public static function GetSelectList(): array
  {
    return MrCacheHelper::getCachedData('CurrencySelectList', function() {
      $out = array();
      foreach(MrCurrency::all() as $item) {
        $out[$item->id()] = $item->getTextCode();
      }

      return $out;
    });
  }

  /**
   * Список для выпадушки с основными валютами
   *
   * @return array
   */
  public static function GetSelectBaseList(): array
  {
    return MrCacheHelper::getCachedData('SelectBaseList', function() {
      $list = DB::table(self::getTableName())->whereIn('TextCode', self::$base_currency)->get(['id', 'TextCode']);
      $out = array();
      foreach($list as $item) {
        $out[$item->id] = $item->TextCode;
      }

      return $out;
    });
  }

  public static function getPopupListVue(): array
  {
    $out = array();
    foreach(self::GetSelectBaseList() as $key => $item) {
      $out[] = array('id' => $key, 'Name' => $item);
    }

    return $out;
  }

  /**
   * Сконвертирует в валюту по умолчанию
   *
   * @param float $sum
   * @param MrCurrency $currency
   * @return float
   */
  public static function convertToPrimary(float $sum, MrCurrency $currency): float
  {
    if($currency->id() === self::BYN()) {
      return $sum;
    }

    $currency_rate = MrCurrencyRate::where('CurrencyID', $currency->id())->orderBy('id', 'desc')->first();
    if(!$currency_rate) {
      return $sum;
    }

    return $sum * $currency_rate->getRate();
  }

  private static array $currencyRateList = array();

  /**
   * Сконвертирует в валюту по умолчанию. не для объектов
   *
   * @param float $sum
   * @param int $currencyId
   * @return float
   */
  public static function convertToPrimaryArray(float $sum, int $currencyId): float
  {
    if($currencyId === self::BYN()) {
      return $sum;
    }

    if(!isset(self::$currencyRateList[$currencyId])) {
      $currency_rate = MrCurrencyRate::where('CurrencyID', $currencyId)->orderBy('id', 'desc')->first();
      self::$currencyRateList[$currencyId] = $currency_rate;
    }

    if(!self::$currencyRateList[$currencyId]) {
      return $sum;
    }

    return $sum * self::$currencyRateList[$currencyId]->getRate();
  }

  public static function getPrimaryCurrency(): self
  {
    return self::loadBy(MrCurrency::BYN());
  }
}
