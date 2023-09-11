<?php

namespace App\Models;

use App\Models\ORM\ORM;

class TravelImage extends ORM
{
  protected $table = 'travel_image';

  protected $fillable = [
    'travel_id',
    'kind',
    'name',
  ];

  const KIND_MAIN = 0;
  const KIND_LIST = 1;

  protected static array $kinds = [
    self::KIND_MAIN => 'Главное',
    self::KIND_LIST => 'Список',
  ];

  public function travel()
  {
    return $this->belongsTo(Travel::class);
  }
}
