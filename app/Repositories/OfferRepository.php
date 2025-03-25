<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Offer;
use App\Services\OfferRepositoryInterface;
use App\Services\ParsingService\DTO\OfferDto;

final readonly class OfferRepository extends DatabaseRepository implements OfferRepositoryInterface
{
    public function getOfferById(int $id): Offer
    {
        return Offer::loadByOrDie($id);
    }

    public function saveOffer(OfferDto $dto): int
    {
        return $this->db->table(Offer::getTableName())->insertGetId($dto->jsonSerialize());
    }

    public function getOffersByLinkId(int $linkId): array
    {
        return $this->db->table(Offer::getTableName())
            ->where('link_id', $linkId)
            ->get()
            ->keyBy('offer_id')
            ->toArray();
    }
}
