<?php

namespace App\Models;

use App\Models\Lego\Fields\CreatedFieldTrait;
use App\Models\Lego\Fields\KindFieldTrait;
use App\Models\Lego\Fields\NameFieldTrait;
use App\Models\ORM\ORM;

class TravelImage extends ORM
{
  use NameFieldTrait;
  use CreatedFieldTrait;
  use KindFieldTrait;

  protected $table = 'travel_images';

  public $timestamps = false;

  protected $fillable = [
    'travel_id',
    'kind',
    'name',
  ];

  const KIND_MAIN = 0;
  const KIND_LIST = 1;

  public static function getKindList(): array
  {
    return [
      self::KIND_MAIN => 'Главное',
      self::KIND_LIST => 'Список',
    ];
  }

  #region ORM
  public function afterSave(): void
  {
    $this->flushAffectedCaches();
  }

  public function beforeDelete(): void
  {
    $this->flushAffectedCaches();
  }

  public function flushAffectedCaches(): void
  {
    $this->getTravel()->flush();
  }

  #endregion

  public function getTravel(): Travel
  {
    return Travel::loadByOrDie($this->travel_id);
  }

  public function setTravelID(int $value): void
  {
    $this->travel_id = $value;
  }
}
