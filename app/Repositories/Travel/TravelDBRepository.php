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

  public function updateTravel(int $id, array $data): void
  {
    $this->db->table(Travel::getTableName())->where('id', $id)->update($data);
  }
}
