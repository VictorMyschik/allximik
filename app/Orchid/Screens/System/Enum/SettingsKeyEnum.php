<?php

declare(strict_types=1);

namespace App\Orchid\Screens\System\Enum;

enum SettingsKeyEnum: string
{
    case DefaultCurrency = 'default_currency';

    public function getLabel(): string
    {
        return match ($this) {
            self::DefaultCurrency => 'Валюта по умолчанию',
        };
    }

    public static function getSelectList(): array
    {
        return [
            self::DefaultCurrency->value => self::DefaultCurrency->getLabel(),
        ];
    }
}
