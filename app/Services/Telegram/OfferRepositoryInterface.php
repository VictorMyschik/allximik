<?php

namespace App\Services\Telegram;

use App\Models\Offer;

interface OfferRepositoryInterface
{
    public function getOfferById(int $id): Offer;
}
