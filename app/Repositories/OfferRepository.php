<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Offer;
use App\Services\Telegram\OfferRepositoryInterface;

final readonly class OfferRepository extends DatabaseRepository implements OfferRepositoryInterface
{
    public function getOfferById(int $id): Offer
    {
        return Offer::loadByOrDie($id);
    }
}
