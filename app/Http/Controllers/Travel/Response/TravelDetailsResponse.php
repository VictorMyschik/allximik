<?php

declare(strict_types=1);

namespace App\Http\Controllers\Travel\Response;

use App\Http\Controllers\Response\CountryResponse;
use App\Http\Controllers\Response\TravelTypeResponse;
use App\Http\Controllers\Travel\Response\Components\TravelStatusComponent;
use App\Http\Controllers\Travel\Response\Components\TravelUserComponent;
use App\Http\Controllers\Travel\Response\Components\TravelVisibleKind;

final readonly class TravelDetailsResponse
{
    public function __construct(
// 'id'          => $travel->id(),
//      'name'        => $travel->getName(),
//      'description' => $travel->getDescription(),
//
//      'status' => [
//        'key'  => $travel->getStatus(),
//        'name' => $travel->getStatusName(),
//      ],
//
//      'visible_kind' => [
//        'key'  => $travel->getVisibleKind(),
//        'name' => $travel->getVisibleKindName(),
//      ],
//
//      'user' => [
//        'name'  => $travel->getUser()->name,
//        'email' => $travel->getUser()->email,
//      ],
//
//      'country' => [
//        'id'        => $travel->getCountry()->id(),
//        'name'      => $travel->getCountry()->getName(),
//        'continent' => [
//          'name'       => $travel->getCountry()->getContinentName(),
//          'short_name' => $travel->getCountry()->getContinentShortName(),
//        ],
//      ],
//
//      'travel_type' => [
//        'id'          => $travel->getTravelType()->id(),
//        'name'        => $travel->getTravelType()->getName(),
//        'description' => $travel->getTravelType()->getDescription(),
//      ],
//
//      'created_at' => $travel->getCreatedObject()->format(MrDateTime::SHORT_DATE),
//      'updated_at' => $travel->getUpdatedObject()?->format(MrDateTime::SHORT_DATE),
//
//      'images' => [
//        'main' => $travel->getMainImage(),
//        'list' => $travel->getImagesList(),
//      ]
        public int                   $id,
        public string                $name,
        public string                $description,
        public TravelStatusComponent $status,
        public TravelVisibleKind     $visible_kind,
        public TravelUserComponent   $user,
        public CountryResponse       $country,
        public TravelTypeResponse    $travel_type,
        public string                $created_at,
        public ?string               $updated_at,
        public array                 $images = [],
    ) {}
}
