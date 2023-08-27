<?php

namespace App\Models\Reference;

use App\Helpers\System\MrCacheHelper;
use App\Models\Lego\Fields\DescriptionNullableFieldTrait;
use App\Models\Lego\Fields\NameFieldTrait;
use App\Models\ORM\ORM;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Currency extends ORM
{
  use AsSource;
  use Filterable;

  use DescriptionNullableFieldTrait;
  use NameFieldTrait;

  public $timestamps = false;

  protected $table = 'currency';

  protected array $allowedSorts = [
    'rate',
    'scale',
    'currency_id',
  ];

  protected $fillable = array(
    'rate',
    'scale',
    'currency_id',
  );

  public static array $base_currency = ['USD', 'BYN', 'RUR', 'EUR', 'GEL'];

  private static int $usdId = 0;

  public static function USD(): int
  {
    if (self::$usdId) {
      return self::$usdId;
    }

    self::$usdId = Cache::rememberForever('USD', function () {
      return Currency::where('text_code', 'USD')->value('id');
    });

    return self::$usdId;
  }

  //  Цифровой код
  public function getCode(): string
  {
    return $this->code;
  }

  public function setCode(string $value): void
  {
    $this->code = $value;
  }

  public function getTextCode(): string
  {
    return $this->text_code;
  }

  public function setTextCode(string $value): void
  {
    $this->text_code = $value;
  }

  public function getRounding(): ?int
  {
    return $this->rounding;
  }

  public function setRounding(int $value): void
  {
    $this->rounding = $value;
  }

  public static function getSelectList(): array
  {
    return Cache::rememberForever('CurrencySelectList', function () {
      $out = array();
      foreach (Currency::all() as $item) {
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
    return MrCacheHelper::getCachedData('SelectBaseList', function () {
      $list = DB::table(self::getTableName())->whereIn('TextCode', self::$base_currency)->get(['id', 'TextCode']);
      $out = array();
      foreach ($list as $item) {
        $out[$item->id] = $item->TextCode;
      }

      return $out;
    });
  }

  public static function getPopupListVue(): array
  {
    $out = array();
    foreach (self::GetSelectBaseList() as $key => $item) {
      $out[] = array('id' => $key, 'Name' => $item);
    }

    return $out;
  }

  /**
   * Сконвертирует в валюту по умолчанию
   *
   * @param float $sum
   * @param Currency $currency
   * @return float
   */
  public static function convertToPrimary(float $sum, Currency $currency): float
  {
    if ($currency->id() === self::BYN()) {
      return $sum;
    }

    $currency_rate = CurrencyRate::where('CurrencyID', $currency->id())->orderBy('id', 'desc')->first();
    if (!$currency_rate) {
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
    if ($currencyId === self::BYN()) {
      return $sum;
    }

    if (!isset(self::$currencyRateList[$currencyId])) {
      $currency_rate = CurrencyRate::where('CurrencyID', $currencyId)->orderBy('id', 'desc')->first();
      self::$currencyRateList[$currencyId] = $currency_rate;
    }

    if (!self::$currencyRateList[$currencyId]) {
      return $sum;
    }

    return $sum * self::$currencyRateList[$currencyId]->getRate();
  }

  public static function getPrimaryCurrency(): self
  {
    return self::loadBy(Currency::BYN());
  }
}
