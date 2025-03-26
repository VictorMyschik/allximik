<?php

namespace App\Services;

use App\Models\Link;
use App\Models\Offer;
use App\Services\ParsingService\Enum\SiteType;

interface OfferRepositoryInterface
{
    public function getOfferById(int $id): Offer;

    public function saveOfferLinkList(Link $link, array $dtos): array;

    public function getOffersLinksByLinkId(int $linkId): array;

    public function saveOfferList(SiteType $type, array $data): void;
}
