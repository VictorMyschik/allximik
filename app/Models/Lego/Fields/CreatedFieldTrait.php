<?php

namespace App\Models\Lego\Fields;

use Carbon\Carbon;

trait CreatedFieldTrait
{
    public function getCreated(): string
    {
        return $this->created_at;
    }

    public function setCreated(Carbon $value): void
    {
        $this->created_at = $value;
    }

    public function getCreatedObject(): Carbon
    {
        return new Carbon($this->created_at);
    }
}
