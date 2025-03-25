<?php

declare(strict_types=1);

namespace App\Services\ParsingService\Enum;

enum SiteType: string
{
    case OLX = 'www.olx.pl';
    case MAXON = 'www.maxon.pl';

    public static function getSelectList(): array
    {
        return [
            self::OLX->value => 'OLX',
            self::MAXON->value => 'Maxon',
        ];
    }
}
