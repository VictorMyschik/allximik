<?php

namespace App\Services\ParsingService\Sites\Maxon;

interface MaxonClientInterface
{
    public function loadPage(string $path, array $query): string;
}
