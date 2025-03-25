<?php

declare(strict_types=1);

namespace App\Services\Telegram\Enum;

enum MaxonOfferParameters: string
{
    case FLOOR = 'floor_select';
    case METERS = 'm';
    case ROOMS = 'rooms';

    public function getLabel(): string
    {
        return match ($this) {
            self::FLOOR => 'Этаж',
            self::METERS => 'Площадь',
            self::ROOMS => 'Комнаты',
        };
    }

    public static function getSelectList(): array
    {
        return [
            self::FLOOR,
            self::METERS,
            self::ROOMS,
        ];
    }
}
