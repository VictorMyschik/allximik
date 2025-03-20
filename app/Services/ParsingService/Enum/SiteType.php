<?php

declare(strict_types=1);

namespace App\Services\ParsingService\Enum;

enum SiteType: string
{
    case OLX = 'www.olx.pl';

    public static function getSelectList(): array
    {
        return [
            self::OLX->value => 'OLX'
        ];
    }
}
