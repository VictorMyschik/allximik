<?php

namespace App\Models\Lego\Fields;

trait SSOUserIdFieldTrait
{
    public function getSSOUserId(): string
    {
        return $this->sso_user_id;
    }

    public function setSSOUserId(string $value): void
    {
        $this->sso_user_id = $value;
    }
}
