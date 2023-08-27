<?php

namespace App\Models\Lego\Fields;

use App\Models\Hike;

trait HikeFieldTrait
{
  public function getHike(): Hike
  {
    return Hike::findOrFail($this->hike_id);
  }

  public function setHikeID(int $value): void
  {
    $this->hike_id = $value;
  }
}
