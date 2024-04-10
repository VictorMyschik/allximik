<?php

namespace App\Classes\Travel;

use App\Models\Travel;
use App\Models\User;

class TravelClass extends TravelBaseClass
{
  public function __construct(private readonly ?User $user)
  {
  }

  public function createTravel(array $input): Travel
  {
    $travel = new Travel();

    $travel->setTravelTypeID($input['travel_type_id']);
    $travel->setCountryID($input['country_id']);
    $travel->setUserID($input['user_id']);
    $travel->setName($input['name']);
    $travel->setDescription($input['description']);
    $travel->setStatus($input['status']);
    $travel->setVisibleKind($input['visible_kind']);
    $travel->save_mr();

    return $travel;
  }

  /**
   * Using for personal pages. Show draft users travels
   * @return Travel[]
   */
  public function getPersonalList(array $filter = []): array
  {
    return Travel::where('user_id', $this->user->id())->get()->all();
  }

  /**
   * For public pages. Not show draft users travels
   * @return Travel[]
   */
  public function getPublicList(array $filter = []): array
  {
    if (!$this->user) {
      $query = Travel::where('visible_kind', Travel::VISIBLE_KIND_PUBLIC)->whereIn('status', [Travel::STATUS_ACTIVE, Travel::STATUS_ARCHIVED]);
    }

    if ($this->user) {
      $query = Travel::whereIn('visible_kind', [Travel::VISIBLE_KIND_PUBLIC, Travel::VISIBLE_KIND_PLATFORM])
        ->whereIn('status', [Travel::STATUS_ACTIVE, Travel::STATUS_ARCHIVED]);
    }

    // Filtering

    return $query->get()->all();
  }

  public function getConvertedList(): array
  {
    $result = [];

    foreach ($this->getPublicList() as $travel) {
      $result[] = $this->getTravelData($travel);
    }

    return $result;
  }
}
