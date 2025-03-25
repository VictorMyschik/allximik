<?php

namespace App\Services\ParsingService\Maxon;

interface MaxonClientInterface
{
    public function loadPage(string $path, array $query): string;
}
