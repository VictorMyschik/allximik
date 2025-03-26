<?php

declare(strict_types=1);

namespace App\Services\ParsingService;

use App\Jobs\ParseLinkJob;
use App\Jobs\TelegramMessageJob;
use App\Models\Link;
use App\Services\OfferRepositoryInterface;
use Psr\Log\LoggerInterface;

final readonly class RunnerService
{
    public function __construct(
        private LinkRepositoryInterface        $linkRepository,
        private OfferRepositoryInterface       $offerRepository,
        private ParsingServiceFactoryInterface $parsingServiceFactory,
        private LoggerInterface                $logger,
    ) {}

    public function runFromCron(): void
    {
        $list = $this->linkRepository->getLinks();

        foreach ($list as $link) {
            ParseLinkJob::dispatch($link->id, true);
        }
    }

    public function parseOffersByLink(int $linkId, bool $withNotify = false): void
    {
        try {
            $link = $this->linkRepository->getLinkById($linkId);
            $parser = $this->parsingServiceFactory->getSupportedParser($link->getType());
            $parsedData = $parser->parse($link);
            $newOfferIds = $this->saveParsedData($link, $parsedData);

            if ($withNotify && $newOfferIds) {
                $this->notify($newOfferIds, $link);
            }
        } catch (\Throwable $e) {
            $this->logger->error('Error parsing offers by link: ' . $e->getMessage());
            throw $e;
        }
    }

    private function saveParsedData(Link $link, array $data): array
    {
        $this->offerRepository->saveOfferList($link->getType(), $data);

        return $this->offerRepository->saveOfferLinkList($link, $data);
    }

    private function notify(array $offerIds, Link $link): void
    {
        $userIds = $this->linkRepository->getUserIdsByLinkId($link->id());

        foreach ($offerIds as $offerId) {
            TelegramMessageJob::dispatch($offerId, $link->getType(), $userIds);
        }
    }
}
