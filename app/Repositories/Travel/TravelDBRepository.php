<?php

declare(strict_types=1);

namespace App\Repositories\Travel;

use App\Models\Travel;
use App\Repositories\RepositoryBase;
use App\Services\Travel\TravelRepositoryInterface;

class TravelDBRepository extends RepositoryBase implements TravelRepositoryInterface
{
    public function getTravelById(int $id): ?Travel
    {
        return Travel::loadBy($id);
    }

    public function saveTravel(int $id, array $data): int
    {
        if ($id > 0) {
            $data['updated_at'] = now();
            $this->db->table('travels')->where('id', $id)->update($data);
            return $id;
        }

        return $this->db->table('travels')->insertGetId($data);
    }

    public function getTravelFullImages(int $travelId): array
    {
        return $this->db->table('travel_images')->where('travel_id', $travelId)->get()->all();
    }

    public function getTravelByUserId(int $userId): array
    {
        return $this->db->table('travels')->where('user_id', $userId)->get()->all();
    }
}
