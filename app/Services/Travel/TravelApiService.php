<?php

declare(strict_types=1);

namespace App\Services\Travel;

use App\Helpers\System\MrDateTime;
use App\Http\Controllers\Response\Components\CountryContinentComponent;
use App\Http\Controllers\Response\CountryResponse;
use App\Http\Controllers\Response\TravelTypeResponse;
use App\Http\Controllers\Travel\Response\Components\TravelImageComponent;
use App\Http\Controllers\Travel\Response\Components\TravelStatusComponent;
use App\Http\Controllers\Travel\Response\Components\TravelUserComponent;
use App\Http\Controllers\Travel\Response\Components\TravelVisibleKind;
use App\Http\Controllers\Travel\Response\TravelDetailsResponse;
use App\Models\TravelImage;

readonly class TravelApiService extends TravelService
{
    public function getTravelDetailsResponse(int $travelId): TravelDetailsResponse
    {
        $travel = $this->getTravelById($travelId);
        $images = [];

        /** @var TravelImage $image */
        foreach ($this->getTravelFullImages($travelId) as $image) {
            $images[] = new TravelImageComponent(
                logo: $image->getKind() === TravelImage::KIND_LOGO,
                name: $image->getName(),
                url: $image->getUrl(),
                description: $image->getDescription(),
            );
        }

        return new TravelDetailsResponse(
            id: $travelId,
            title: $travel->getTitle(),
            description: $travel->getDescription(),
            status: new TravelStatusComponent(
                key: $travel->getStatus(),
                name: $travel->getStatusName(),
            ),
            visible_kind: new TravelVisibleKind(
                key: $travel->getVisibleKind(),
                name: $travel->getVisibleKindName(),
            ),
            user: new TravelUserComponent(
                name: $travel->getUser()->name,
                email: $travel->getUser()->email,
            ),
            country: new CountryResponse(
                id: $travel->getCountry()->id(),
                name: $travel->getCountry()->getName(),
                continent: new CountryContinentComponent(
                    name: $travel->getCountry()->getContinentName(),
                    short_name: $travel->getCountry()->getContinentShortName(),
                ),
            ),
            travel_type: new TravelTypeResponse(
                id: $travel->getTravelType()->id(),
                name: $travel->getTravelType()->getName(),
                description: $travel->getTravelType()->getDescription(),
            ),
            created_at: $travel->getCreatedObject()->format(MrDateTime::SHORT_DATE),
            updated_at: $travel->getUpdatedObject()?->format(MrDateTime::SHORT_DATE),
            images: $images,
        );
    }
}
