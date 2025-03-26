<?php

declare(strict_types=1);

namespace App\Services\ParsingService\DTO;

final readonly class OfferDto implements \JsonSerializable
{
    public function __construct(
        public int    $offerId,
        public int    $linkId,
        public string $sl,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'offer_id' => $this->offerId,
            'sl'       => $this->sl,
        ];
    }
}
