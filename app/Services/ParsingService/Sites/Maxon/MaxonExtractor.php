<?php

declare(strict_types=1);

namespace App\Services\ParsingService\Sites\Maxon;

use App\Services\ParsingService\ExtractorInterface;
use App\Services\Telegram\Enum\MaxonOfferParameters;

final readonly class MaxonExtractor implements ExtractorInterface
{
    public function __construct(private array $data) {}

    public function getTitle(): string
    {
        return html_entity_decode($this->data['Title']);
    }

    public function getPrice(): string
    {
        return html_entity_decode($this->data['IMD'] ?? '');
    }

    public function getPhoto(): string
    {
        return 'https://cdn.maxon.pl/Photos/' . $this->data['MainPhoto'];
    }

    public function getLink(): string
    {
        return $this->data['URL'];
    }

    public function getParameter(string $key): string
    {
        return match ($key) {
            MaxonOfferParameters::FLOOR->value => $this->extractFloor(),
            MaxonOfferParameters::METERS->value => $this->extractMeters(),
            MaxonOfferParameters::ROOMS->value => $this->extractRooms(),
        };
    }

    public function extractRooms(): string
    {
        foreach ($this->data['InlineItems'] as $parameter) {
            if (str_contains($parameter, 'Liczba pokoi')) {
                $value = html_entity_decode($parameter);
                $value = str_replace('Liczba pokoi', '', $value);
                return str_replace('<span>', '', $value);
            }
        }

        return '';
    }

    private function extractMeters(): string
    {
        foreach ($this->data['InlineItems'] as $parameter) {
            if (str_contains($parameter, 'Powierzchnia')) {
                $value = html_entity_decode($parameter);
                $value = str_replace('Powierzchnia', '', $value);
                $value = str_replace('<sup>2', '²', $value);
                return str_replace('<span>', '', $value);
            }
        }

        return '';
    }

    private function extractFloor(): string
    {
        foreach ($this->data['InlineItems'] as $parameter) {
            if (str_contains($parameter, 'Pi&#281;tro')) {
                $value = html_entity_decode($parameter);
                $value = str_replace('Pi&#281;tro', '', $value);
                return str_replace('<span>', '', $value);
            }
        }

        return '';
    }
}
