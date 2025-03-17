<?php

namespace App\Services\ParsingService\OLX;

interface OlxClientInterface
{
    public function loadPage(array $filter): string;
}
