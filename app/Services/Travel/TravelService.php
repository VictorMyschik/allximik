<?php

declare(strict_types=1);

namespace App\Services\Travel;

use App\Models\Travel;

readonly class TravelService
{
    public function __construct(
        private TravelRepositoryInterface $repository,
    ) {}

    public function getTravelById(int $id): ?Travel
    {
        return $this->repository->getTravelById($id);
    }

    public function updateTravel(int $id, array $data): void
    {
        $this->repository->updateTravel($id, $data);
    }

    public function getTravelFullImages(int $travelId): array
    {
        return $this->repository->getTravelFullImages($travelId);
    }
}
