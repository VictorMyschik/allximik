<?php

namespace App\Models;

use App\Models\Lego\Fields\CreatedFieldTrait;
use App\Models\Lego\Fields\DescriptionNullableFieldTrait;
use App\Models\Lego\Fields\NameFieldTrait;
use App\Models\ORM\ORM;

class Equipment extends ORM
{
  use NameFieldTrait;
  use DescriptionNullableFieldTrait;
  use CreatedFieldTrait;

  protected $table = 'equipments';
  public $timestamps = false;

  protected $fillable = array(
    'name',
    'description',
    'category_id',
  );

  public function getCategory(): ?CategoryEquipment
  {
    return CategoryEquipment::loadBy($this->category_id);
  }

  public function setCategoryID(?int $value): void
  {
    $this->category_id = $value;
  }
}
