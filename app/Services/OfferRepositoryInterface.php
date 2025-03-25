<?php

namespace App\Services;

use App\Models\Offer;
use App\Services\ParsingService\DTO\OfferDto;

interface OfferRepositoryInterface
{
    public function getOfferById(int $id): Offer;

    public function saveOffer(OfferDto $dto): int;

    public function getOffersByLinkId(int $linkId): array;
}
