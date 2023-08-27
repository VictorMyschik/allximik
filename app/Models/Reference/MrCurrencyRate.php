<?php

namespace App\Models\Reference;

use App\Models\Lego\Fields\CreatedFieldTrait;
use App\Models\Lego\Fields\UpdatedNullableFieldTrait;
use App\Models\ORM\ORM;

class MrCurrencyRate extends ORM
{
  use CreatedFieldTrait;
  use UpdatedNullableFieldTrait;

  protected $table = 'currency_rate';
  protected $fillable = array(
    'currency_id',
    'scale',
    'rate',
  );

  public function getCurrency(): Currency
  {
    return Currency::loadByOrDie($this->currency_id);
  }

  public function setCurrencyID(int $value): void
  {
    $this->currency_id = $value;
  }

  /**
   * Количество единиц валюты
   */
  public function getScale(): int
  {
    return $this->scale;
  }

  public function setScale(int $value): void
  {
    $this->scale = $value;
  }

  public function getRate(): float
  {
    return $this->rate;
  }

  public function setRate(float $value): void
  {
    $this->rate = $value;
  }
}
