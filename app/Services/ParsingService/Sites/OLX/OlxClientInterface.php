<?php

namespace App\Services\ParsingService\Sites\OLX;

interface OlxClientInterface
{
    public function loadPage(string $path, array $query): string;
}
