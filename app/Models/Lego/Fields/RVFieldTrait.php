<?php

namespace App\Models\Lego\Fields;

use App\Models\RV;

trait RVFieldTrait
{
    public function getRV(): RV
    {
        return RV::loadBy($this->rv_id);
    }

    public function setRvID(int $value): void
    {
        $this->rv_id = $value;
    }
}
