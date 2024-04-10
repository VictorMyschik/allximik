<?php

namespace App\Services\Travel;

use App\Models\Travel;

interface TravelRepositoryInterface
{
    public function getTravelById(int $id): ?Travel;

    public function saveTravel(int $id, array $data): int;

    public function getTravelFullImages(int $travelId): array;
}
