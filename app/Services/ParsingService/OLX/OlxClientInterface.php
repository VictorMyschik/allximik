<?php

namespace App\Services\ParsingService\OLX;

interface OlxClientInterface
{
    public function loadPage(string $path, array $query): string;
}
