<?php

namespace App\Services\ParsingService;

use App\Services\ParsingService\Enum\SiteType;

interface ParsingServiceFactoryInterface
{
    public function getSupportedParser(SiteType $type): ParsingStrategyInterface;
}
