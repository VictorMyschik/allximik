<?php

declare(strict_types=1);

namespace App\Services\ParsingService\OLX;

use App\Services\ParsingService\ExtractorInterface;

final readonly class OLXExtractor implements ExtractorInterface
{
    public function __construct(private array $data) {}

    public function getTitle(): string
    {
        return html_entity_decode($this->data['title']);
    }

    public function getPrice(): string
    {
        return html_entity_decode($this->data['price']['displayValue']);
    }

    public function getPhoto(): string
    {
        $src = str_replace('\u002F', '/', $this->data['photos'][0]);
        $src = str_replace('\u002F', '/', $src);

        return $src;
    }

    public function getLink(): string
    {
        $url = str_replace('\u002F', '/', $this->data['url']);
        return str_replace('\u002F', '/', $url);
    }
}
