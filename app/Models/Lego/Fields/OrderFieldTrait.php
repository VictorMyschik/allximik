<?php

namespace App\Models\Lego\Fields;

trait OrderFieldTrait
{
    public function getOrder(): int
    {
        return $this->order;
    }

    public function setOrder(int $value): void
    {
        $this->order = $value;
    }
}
