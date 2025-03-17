<?php

namespace App\Services\ParsingService\OLX;

interface OlxRepositoryInterface
{
    public function saveOffer(string $offerId, int $linkId, string $sl): int;
}
