<?php

namespace App\Models\Reference;

use App\Models\Lego\Fields\NameFieldTrait;
use App\Models\ORM\ORM;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class MrMeasure extends ORM
{
  use AsSource;
  use Filterable;

  use NameFieldTrait;

  protected $table = 'measure';
  protected $fillable = array(
    'code',
    'text_code',
    'name',
  );

  //  Цифровой код
  public function getCode(): string
  {
    return $this->code;
  }

  public function setCode(string $value): void
  {
    $this->code = $value;
  }

  // Текстовый код
  public function getTextCode(): string
  {
    return $this->text_code;
  }

  public function setTextCode(string $value): void
  {
    $this->text_code = $value;
  }
}
