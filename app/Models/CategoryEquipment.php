<?php

namespace App\Models;

use App\Models\Lego\Fields\DescriptionNullableFieldTrait;
use App\Models\Lego\Fields\NameFieldTrait;
use App\Models\ORM\ORM;

class CategoryEquipment extends ORM
{
  use NameFieldTrait;
  use DescriptionNullableFieldTrait;

  public $timestamps = false;

  protected $table = 'category_equipments';

  protected $fillable = array(
    'name',
    'description',
  );
}
