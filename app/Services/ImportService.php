<?php

declare(strict_types=1);

namespace App\Services;

use App\Jobs\ParseLinkJob;
use App\Services\ParsingService\Enum\SiteType;
use App\Services\ParsingService\LinkRepositoryInterface;

final readonly class ImportService
{
    public function __construct(
        private LinkRepositoryInterface $linkRepository,
    ) {}

    public function import(string $url, string $user = 'me'): void
    {
        $url = parse_url($url);
        $type = SiteType::tryFrom($url['host']);

        $newLinkId = match ($type) {
            SiteType::OLX => $this->importLink($user, $type, $url['path'], $url['query']),
            default => throw new \Exception('Unsupported site type'),
        };

        ParseLinkJob::dispatch($newLinkId);
    }

    private function importLink(string $user, SiteType $type, string $path, string $query): int
    {
        return $this->linkRepository->createLink($user, $type, $path, $query);
    }
}
