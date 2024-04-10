<?php

declare(strict_types=1);

namespace App\Services\Travel;

use App\Models\Travel;
use App\Models\User;

readonly class TravelService
{
    public function __construct(
        private TravelRepositoryInterface $repository,
    ) {}

    /**
     * For public pages. Not show draft users travels
     * @return Travel[]
     */
    public function getPublicList(?User $user, array $filter = []): array
    {
        if (!$user) {
            $query = Travel::where('visible_kind', Travel::VISIBLE_KIND_PUBLIC)->whereIn('status', [Travel::STATUS_ACTIVE, Travel::STATUS_ARCHIVED]);
        }

        if ($user) {
            $query = Travel::whereIn('visible_kind', [Travel::VISIBLE_KIND_PUBLIC, Travel::VISIBLE_KIND_PLATFORM])
                ->whereIn('status', [Travel::STATUS_ACTIVE, Travel::STATUS_ARCHIVED]);
        }

        // Filtering

        return $query->get()->all();
    }

    public function getTravelById(int $id): ?Travel
    {
        return $this->repository->getTravelById($id);
    }

    public function getTravelByUserId(int $userId): array
    {
        return $this->repository->getTravelByUserId($userId);
    }

    public function saveTravel(int $id, array $data): int
    {
        return $this->repository->saveTravel($id, $data);
    }

    public function getTravelFullImages(int $travelId): array
    {
        return $this->repository->getTravelFullImages($travelId);
    }
}
