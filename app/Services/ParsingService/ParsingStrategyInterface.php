<?php

namespace App\Services\ParsingService;

use App\Models\Link;

interface ParsingStrategyInterface
{
    public function parse(Link $link): array;
}
