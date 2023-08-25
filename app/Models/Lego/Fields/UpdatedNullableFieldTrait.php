<?php

namespace App\Models\Lego\Fields;

use Carbon\Carbon;

trait UpdatedNullableFieldTrait
{
    public function getUpdated(): ?string
    {
        return $this->updated_at;
    }

    public function setUpdated(?Carbon $value): void
    {
        $this->updated_at = $value;
    }

    public function getUpdatedObject(): ?Carbon
    {
        return $this->updated_at ? new Carbon($this->updated_at) : null;
    }
}
