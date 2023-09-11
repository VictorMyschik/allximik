<?php

namespace App\Classes\Travel;

use App\Models\Travel;

class TravelClass extends TravelBaseClass
{
  /**
   * @return Travel[]
   */
  public function getList(): array
  {
    return Travel::where('visible_kind', Travel::VISIBLE_KIND_PUBLIC)->get()->all();
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
      $result[] = $this->getConvertTravel($travel);
    }

    return $result;
  }
}
