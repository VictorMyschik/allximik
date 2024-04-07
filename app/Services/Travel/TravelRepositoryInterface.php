<?php

namespace App\Services\Travel;

use App\Models\Travel;

interface TravelRepositoryInterface
{
  public function getTravelById(int $id): ?Travel;

  public function updateTravel(int $id, array $data): void;
}
