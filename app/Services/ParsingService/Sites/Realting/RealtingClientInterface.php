<?php

namespace App\Services\ParsingService\Sites\Realting;

interface RealtingClientInterface
{
    public function loadPage(string $path, array $query): string;
}
