<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use App\Services\ParsingService\Enum\SiteType;
use App\Services\ParsingService\ExtractorInterface;
use App\Services\ParsingService\Maxon\MaxonExtractor;
use App\Services\ParsingService\OLX\OLXExtractor;
use App\Services\ParsingService\Realting\RealtingExtractor;

final readonly class OfferExtractor implements ExtractorInterface
{
    private ExtractorInterface $extractor;

    public function __construct(private SiteType $type, array $data)
    {
        $this->extractor = match ($this->type) {
            SiteType::OLX => new OLXExtractor($data),
            SiteType::MAXON => new MaxonExtractor($data),
            SiteType::REALTING => new RealtingExtractor($data),
        };
    }

    public function getTitle(): string
    {
        return $this->extractor->getTitle();
    }

    public function getPrice(): string
    {
        return $this->extractor->getPrice();
    }

    public function getPhoto(): string
    {
        return $this->extractor->getPhoto();
    }

    public function getLink(): string
    {
        return $this->extractor->getLink();
    }

    public function getParameter(string $key): string
    {
        return $this->extractor->getParameter($key);
    }
}
