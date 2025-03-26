<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Link;
use App\Models\Offer;
use App\Models\OfferLink;
use App\Services\OfferRepositoryInterface;
use App\Services\ParsingService\DTO\OfferDto;
use App\Services\ParsingService\Enum\SiteType;

final readonly class OfferRepository extends DatabaseRepository implements OfferRepositoryInterface
{
    public function getOfferById(int $id): Offer
    {
        return Offer::loadByOrDie($id);
    }

    public function saveOfferLinkList(Link $link, array $dtos): array
    {
        $offerIds = $this->db->table(Offer::getTableName())
            ->where('type', $link->getType()->value)
            ->whereIn('offer_id', array_column($dtos, 'offerId'))
            ->pluck('id', 'offer_id')
            ->toArray();

        $offerLinkIds = $this->getOffersLinksByLinkId($link->id());

        $data = [];
        /** @var OfferDto $dto */
        foreach ($dtos as $dto) {
            $offerId = $offerIds[$dto->offerId];

            if(!array_key_exists($offerId, $offerLinkIds)) {
                $data[$dto->offerId] = [
                    'offer_id' => $offerId,
                    'link_id'  => $dto->linkId,
                ];
            }
        }

        $this->db->table(OfferLink::getTableName())->insert($data);

        return array_column($data, 'offer_id');
    }

    public function getOffersLinksByLinkId(int $linkId): array
    {
        return $this->db->table(OfferLink::getTableName())
            ->where('link_id', $linkId)
            ->pluck('id', 'offer_id')
            ->toArray();
    }

    /**
     * @param OfferDto[] $data
     */
    public function saveOfferList(SiteType $type, array $data): void
    {
        $existingList = $this->db->table(Offer::getTableName())
            ->where('type', $type->value)
            ->whereIn('offer_id', array_column($data, 'offerId'))
            ->pluck('offer_id')
            ->toArray();

        $dataKeyedById = array_column($data, null, 'offerId');
        $newData = array_diff_key($dataKeyedById, array_flip($existingList));

        if (!empty($newData)) {
            $insertData = [];

            foreach ($newData as $item) {
                $insertData[] = [
                    'type'     => $type->value,
                    'offer_id' => $item->offerId,
                    'sl'       => $item->sl,
                ];
            }

            $this->db->table(Offer::getTableName())->insert($insertData);
        }
    }
}
