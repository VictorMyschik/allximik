<?php

namespace App\Models\Lego\Fields;

trait DescriptionNullableFieldTrait
{
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $value): void
    {
        $this->description = $value;
    }
}
