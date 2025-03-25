<?php

namespace App\Services\ParsingService\Realting;

interface RealtingClientInterface
{
    public function loadPage(string $path, array $query): string;
}
