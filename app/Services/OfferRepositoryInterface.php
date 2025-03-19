<?php

namespace App\Services;

use App\Models\Offer;

interface OfferRepositoryInterface
{
    public function getOfferById(int $id): Offer;

    public function saveOffer(string $offerId, int $linkId, string $sl): int;

    public function getOffersByLinkId(int $linkId): array;
}
