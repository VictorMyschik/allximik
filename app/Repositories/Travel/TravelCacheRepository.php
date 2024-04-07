<?php

declare(strict_types=1);

namespace App\Repositories\Travel;

use App\Models\Travel;
use App\Services\Travel\TravelRepositoryInterface;
use Psr\SimpleCache\CacheInterface;

readonly class TravelCacheRepository implements TravelRepositoryInterface
{
    private const TRAVEL_ID = 'travel_';

    public function __construct(
        private TravelRepositoryInterface $repository,
        private CacheInterface            $cache
    ) {}

    public function getTravelById(int $id): ?Travel
    {
        return $this->cache->rememberForever(self::TRAVEL_ID . $id, function () use ($id) {
            return $this->repository->getTravelById($id);
        });
    }

    public function updateTravel(int $id, array $data): void
    {
        $this->repository->updateTravel($id, $data);
        $this->clearCache($id);
    }

    private function clearCache(int $id): void
    {
        $this->cache->delete(self::TRAVEL_ID . $id);
    }
}
