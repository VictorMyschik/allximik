<?php

declare(strict_types=1);

namespace App\Services\ParsingService\Sites\Realting;

use App\Services\ParsingService\ExtractorInterface;
use App\Services\Telegram\Enum\RealtingOfferParameters;

final readonly class RealtingExtractor implements ExtractorInterface
{
    public function __construct(private array $data) {}

    public function getTitle(): string
    {
        return html_entity_decode($this->data['title']);
    }

    public function getPrice(): string
    {
        return html_entity_decode($this->data['price'] ?? '');
    }

    public function getPhoto(): string
    {
        return '';
    }

    public function getLink(): string
    {
        return $this->data['link'];
    }

    public function getParameter(string $key): string
    {
        return match ($key) {
            RealtingOfferParameters::METERS->value => $this->data['area'],
            RealtingOfferParameters::ROOMS->value => $this->data['rooms'],
        };
    }
}
