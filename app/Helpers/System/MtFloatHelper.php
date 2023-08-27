<?php

namespace App\Helpers\System;

use App\Models\Reference\Currency;
use Exception;

class MtFloatHelper
{
  public static function format(float $sum, int $decimals = 0, bool $thousands_separator = false): string
  {
    return number_format($sum, $decimals, '.', $thousands_separator ? ' ' : '');
  }

  public static function canConvert(string $str): bool
  {
    try {
      self::toFloat($str);
    } catch (\Exception $exception) {
      return false;
    }

    return true;
  }

  /**
   * Форматирование с заданным количеством символов после запятой (добавление нулями)
   */
  public static function formatCommon(float $value, int $max_decimals = 6): string
  {
    $s = '';

    if ($value < 0) {
      $s .= '-';
      $value = -$value;
    }

    $s .= number_format(floor($value), 0, '.', ' ');

    if ($value != floor($value)) {
      $fraction = substr(self::format($value - floor($value), $max_decimals), 1);
      $s .= rtrim($fraction, '0');
    }

    return $s;
  }

  /**
   * @param float $sum
   * @param Currency|null $currency Валюта, по-умолчанию - бел.руб.
   * @param bool $sum_only не добавлять обозначение валюты
   * @param int|null $rounding принудительное округление (если не задано, используется значение из справочника валют).
   * @return string
   */
  public static function formatMoney(float $sum, ?Currency $currency = null, bool $sum_only = false, ?int $rounding = null): string
  {
    if (!$currency) {
      $currency = Currency::loadByOrDie(Currency::BYN());
    }

    $rounding = is_null($rounding) ? $currency->getRounding() : (int)$rounding;

    return number_format($sum, $rounding, '.', ' ') . ($sum_only ? '' : ' ' . $currency->getTextCode());
  }

  public static function toFloat($str): float
  {
    if (is_float($str))
      return $str;

    if (is_null($str))
      throw new \Exception('$str is null');
    if (is_int($str))
      return (float)$str; //casting int to float is always safe

    //убираем все пробелы
    $str = preg_replace('/\s/', '', $str);

    //точки заменяем на запятые
    $str = preg_replace('/\./', ',', $str);

    //leave only last point
    $l = strlen($str);
    $last_point = -1;
    for ($i = 0; $i < $l; $i++) {
      if ($str[$i] === ',')
        $last_point = $i;
    }

    if ($last_point >= 0) {
      $str[$last_point] = '.';
    }

    $str = preg_replace('/\,/', '', $str);

    if (is_numeric($str))
      return (float)$str;
    else
      throw new Exception();
  }

  /**
   * Возвращает сумму прописью
   * https://habrahabr.ru/post/53210/
   *
   * @param float $sum
   * @param array $morphemes_ruble массив словоформ
   * @param array $morphemes_kopeyka массив словоформ
   *
   * @return string
   */
  public static function toMoneyString(float $sum, array $morphemes_ruble = array('белорусский рубль', 'белорусских рубля', 'белорусских рублей'), array $morphemes_kopeyka = array('копейка', 'копейки', 'копеек')): string
  {
    $nul = 'ноль';

    $ten = array(
      array('', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'),
      array('', 'одна', 'две', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'),
    );

    $a20 = array('десять', 'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать', 'шестнадцать', 'семнадцать', 'восемнадцать', 'девятнадцать');
    $tens = array(2 => 'двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто');
    $hundred = array('', 'сто', 'двести', 'триста', 'четыреста', 'пятьсот', 'шестьсот', 'семьсот', 'восемьсот', 'девятьсот');
    $unit = array( // Units
      array($morphemes_kopeyka[0], $morphemes_kopeyka[1], $morphemes_kopeyka[2], 1),
      array($morphemes_ruble[0], $morphemes_ruble[1], $morphemes_ruble[2], 0),
      array('тысяча', 'тысячи', 'тысяч', 1),
      array('миллион', 'миллиона', 'миллионов', 0),
      array('миллиард', 'милиарда', 'миллиардов', 0),
    );


    list($rub, $kop) = explode('.', sprintf("%015.2f", floatval($sum)));
    $out = array();
    if (intval($rub) > 0) {
      foreach (str_split($rub, 3) as $uk => $v) { // by 3 symbols
        if (!intval($v)) continue;
        $uk = sizeof($unit) - $uk - 1; // unit key
        $gender = $unit[$uk][3];
        list($i1, $i2, $i3) = array_map('intval', str_split($v, 1));
        // mega-logic
        $out[] = $hundred[$i1];        # 1xx-9xx
        if ($i2 > 1) $out[] = $tens[$i2] . ' ' . $ten[$gender][$i3]; # 20-99
        else $out[] = $i2 > 0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
        // units without rub & kop
        if ($uk > 1) $out[] = self::_morph($v, $unit[$uk][0], $unit[$uk][1], $unit[$uk][2]);
      } //foreach
    } else $out[] = $nul;
    $out[] = self::_morph(intval($rub), $unit[1][0], $unit[1][1], $unit[1][2]);      // rub
    $out[] = $kop . ' ' . self::_morph($kop, $unit[0][0], $unit[0][1], $unit[0][2]); // kop

    return trim(preg_replace('/ {2,}/', ' ', join(' ', $out)));
  }

  /**
   * Склоняем словоформу
   * @author runcore
   */
  private static function _morph($n, $f1, $f2, $f5)
  {
    $n = abs(intval($n)) % 100;
    if ($n > 10 && $n < 20) return $f5;
    $n = $n % 10;
    if ($n > 1 && $n < 5) return $f2;
    if ($n === 1) return $f1;

    return $f5;
  }
}
