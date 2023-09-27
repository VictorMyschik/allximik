<?php

namespace App\Classes\Travel;

use App\Models\Travel;

class TravelClass extends TravelBaseClass
{
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
   * @return Travel[]
   */
  public function getList(): array
  {
    return Travel::where('visible_kind', Travel::VISIBLE_KIND_PUBLIC)->where('status', Travel::STATUS_ACTIVE)->get()->all();
  }

  public function getConvertedList(): array
  {
    $travels = $this->getList();

    return $this->convertTravelList($travels);
  }

  /**
   * @param Travel[] $travels
   * @return array
   */
  public function convertTravelList(array $travels): array
  {
    $result = [];

    foreach ($travels as $travel) {
      $result[] = $this->getTravelData($travel);
    }

    return $result;
  }
}
