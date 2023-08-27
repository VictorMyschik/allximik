<?php

namespace App\Models;

use App\Models\Lego\Fields\DescriptionNullableFieldTrait;
use App\Models\Lego\Fields\NameFieldTrait;
use App\Models\ORM\ORM;

class HikeType extends ORM
{
  use NameFieldTrait;
  use DescriptionNullableFieldTrait;

  protected $table = 'hike_type';

  protected $fillable = [
    'name',
    'description',
  ];
}
