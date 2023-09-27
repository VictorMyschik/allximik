<?php

namespace App\Classes\Travel;

use App\Helpers\System\MrDateTime;
use App\Models\Travel;

class TravelBaseClass
{
  public function getTravelData(Travel $travel): array
  {
    $out = [
      'id'          => $travel->id(),
      'name'        => $travel->getName(),
      'description' => $travel->getDescription(),

      'status' => [
        'key'  => $travel->getStatus(),
        'name' => $travel->getStatusName(),
      ],

      'visible_kind' => [
        'key'  => $travel->getVisibleKind(),
        'name' => $travel->getVisibleKindName(),
      ],

      'user' => [
        'name'  => $travel->getUser()->name,
        'email' => $travel->getUser()->email,
      ],

      'country' => [
        'id'           => $travel->getCountry()->id(),
        'name'         => $travel->getCountry()->getName(),
        'continent_id' => [
          'name'       => $travel->getCountry()->getContinentName(),
          'short_name' => $travel->getCountry()->getContinentShortName(),
        ],
      ],

      'travel_type' => [
        'id'          => $travel->getTravelType()->id(),
        'name'        => $travel->getTravelType()->getName(),
        'description' => $travel->getTravelType()->getDescription(),
      ],

      'created_at' => $travel->getCreatedObject()->format(MrDateTime::SHORT_DATE),
      'updated_at' => $travel->getUpdatedObject()?->format(MrDateTime::SHORT_DATE),

      'images' => [
        'main' => $travel->getMainImage(),
        'list' => $travel->getImagesList(),
      ]
    ];

    return $out;
  }
}
