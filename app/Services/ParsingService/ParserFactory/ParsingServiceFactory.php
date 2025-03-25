<?php

declare(strict_types=1);

namespace App\Services\ParsingService\ParserFactory;

use App\Services\ParsingService\Enum\SiteType;
use App\Services\ParsingService\Maxon\MaxonClientInterface;
use App\Services\ParsingService\Maxon\MaxonParseService;
use App\Services\ParsingService\OLX\OlxClientInterface;
use App\Services\ParsingService\OLX\OlxParseService;
use App\Services\ParsingService\ParsingServiceFactoryInterface;
use App\Services\ParsingService\ParsingStrategyInterface;
use App\Services\ParsingService\Realting\RealtingClientInterface;
use App\Services\ParsingService\Realting\RealtingParseService;
use Psr\Log\LoggerInterface;

final readonly class ParsingServiceFactory implements ParsingServiceFactoryInterface
{
    public function __construct(
        private OlxClientInterface      $olxClient,
        private MaxonClientInterface    $maxonClient,
        private RealtingClientInterface $realtingClient,
        private LoggerInterface         $logger,
    ) {}

    public function getSupportedParser(SiteType $type): ParsingStrategyInterface
    {
        return match ($type) {
            SiteType::OLX => new OlxParseService($this->olxClient, $this->logger),
            SiteType::MAXON => new MaxonParseService($this->maxonClient, $this->logger),
            SiteType::REALTING => new RealtingParseService($this->realtingClient, $this->logger),
        };
    }
}
