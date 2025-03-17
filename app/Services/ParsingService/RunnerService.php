<?php

declare(strict_types=1);

namespace App\Services\ParsingService;

use App\Jobs\ParseLinkJob;
use App\Services\ParsingService\Enum\SiteType;
use App\Services\ParsingService\OLX\OlxParseService;

final readonly class RunnerService
{
    public function __construct(
        private LinkRepositoryInterface $linkRepository,
        private OlxParseService         $olxParseService,
    ) {}

    public function run(): void
    {
        $list = $this->linkRepository->getLinks();

        foreach ($list as $link) {
            ParseLinkJob::dispatch($link->id());
        }
    }

    public function parseByLink(int $linkId): void
    {
        $link = $this->linkRepository->getLinkById($linkId);

        match ($link->getType()) {
            SiteType::OLX => $this->olxParseService->parse($link),
        };
    }
}
