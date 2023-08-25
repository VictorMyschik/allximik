<?php

namespace App\Models\Reference;

use App\Models\Lego\Traits\Fields\MrNameFieldTrait;
use App\Models\ORM\ORM;

class MrMeasure extends ORM
{
  use MrNameFieldTrait;

  protected $table = 'mr_measure';
  protected $fillable = array(
    'Code',
    'TextCode',
    'Name',
  );

  //  Цифровой код
  public function getCode(): string
  {
    return $this->Code;
  }

  public function setCode(string $value): void
  {
    $this->Code = $value;
  }

  // Текстовый код
  public function getTextCode(): string
  {
    return $this->TextCode;
  }

  public function setTextCode(string $value): void
  {
    $this->TextCode = $value;
  }
}