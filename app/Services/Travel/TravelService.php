<?php

namespace App\Services\Travel;

use App\Models\Travel;

class TravelService
{
  public function __construct(
    private readonly TravelRepositoryInterface $repository,
  ) {}

  public function getTravelById(int $id): ?Travel
  {
    return $this->repository->getTravelById($id);
  }

  public function updateTravel(int $id, array $data): void
  {
    $this->repository->updateTravel($id, $data);
  }
}
