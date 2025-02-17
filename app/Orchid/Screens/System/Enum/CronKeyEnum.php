<?php

declare(strict_types=1);

namespace App\Orchid\Screens\System\Enum;

enum CronKeyEnum: string
{
    case CurrencyRate = 'currency';

    public function getLabel(): string
    {
        return match ($this) {
            self::CurrencyRate => 'Обновление курсов валют',
        };
    }

    public static function getSelectList(): array
    {
        return [
            self::CurrencyRate->value => self::CurrencyRate->getLabel(),
        ];
    }
}
