<?php

namespace App\Services\ParsingService\OLX;

interface OlxRepositoryInterface
{
    public function saveOffer(): int;
}
