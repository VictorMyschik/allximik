<?php

namespace App\Models;

use App\Models\Lego\Fields\DescriptionNullableFieldTrait;
use App\Models\Lego\Fields\NameFieldTrait;
use App\Models\ORM\ORM;

class GlobalCategoryStuff extends ORM
{
  use NameFieldTrait;
  use DescriptionNullableFieldTrait;

  public $timestamps = false;

  protected $table = 'global_category_stuff';

  protected $fillable = array(
    'name',
    'description',
    'parent_id',
  );

  public function getParent(): ?GlobalCategoryStuff
  {
    return GlobalCategoryStuff::loadBy($this->parent_id);
  }

  public function setParentID(int $value): void
  {
    $this->parent_id = $value;
  }
}
