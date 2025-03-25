<?php

declare(strict_types=1);

namespace App\Services\Telegram\Enum;

enum RealtingOfferParameters: string
{
    case METERS = 'm';
    case ROOMS = 'rooms';

    public function getLabel(): string
    {
        return match ($this) {
            self::METERS => 'Площадь',
            self::ROOMS => 'Комнаты',
        };
    }

    public static function getSelectList(): array
    {
        return [
            self::METERS,
            self::ROOMS,
        ];
    }
}
