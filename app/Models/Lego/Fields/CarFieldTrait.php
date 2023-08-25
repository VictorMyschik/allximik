<?php

namespace App\Models\Lego\Fields;

use App\Models\Car;

trait CarFieldTrait
{
    public function getCar(): Car
    {
        return Car::loadBy($this->car_id);
    }

    public function setCarID(int $value): void
    {
        $this->car_id = $value;
    }
}
