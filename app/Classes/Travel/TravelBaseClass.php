<?php

namespace App\Classes\Travel;

use App\Helpers\System\MrDateTime;
use App\Models\Travel;
use App\Models\User;

class TravelBaseClass
{
  public function __construct(private readonly ?User $user)
  {
  }

  public function getConvertTravel(Travel $travel): array
  {
    $out = [
      'id'           => $travel->id(),
      'name'         => $travel->getName(),
      'description'  => $travel->getDescription(),
      'visible_kind' => [
        'key'  => $travel->getVisibleKind(),
        'name' => $travel->getVisibleKindName(),
      ],

      'user_id' => [
        'name'  => $travel->getUser()->name,
        'email' => $travel->getUser()->email,
      ],

      'country_id' => [
        'name'         => $travel->getCountry()->getName(),
        'continent_id' => [
          'name'       => $travel->getCountry()->getContinentName(),
          'short_name' => $travel->getCountry()->getContinentShortName(),
        ],
      ],

      'travel_type_id' => [
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
