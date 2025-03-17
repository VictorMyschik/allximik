<?php

namespace App\Repositories;


use App\Models\Offer;
use App\Services\ParsingService\OLX\OlxRepositoryInterface;

final readonly class OlxRepository extends DatabaseRepository implements OlxRepositoryInterface
{
    public function saveOffer(string $offerId, int $linkId, string $sl): int
    {
        return $this->db->table(Offer::getTableName())->insertGetId([
            'offer_id' => $offerId,
            'link_id'  => $linkId,
            'sl'       => $sl,
        ]);
    }

    public function getOffersByLinkId(int $linkId): array
    {
        return $this->db->table(Offer::getTableName())->where('link_id', $linkId)->get()->keyBy('offer_id')->toArray();
    }
}
