<?php

declare(strict_types=1);

namespace App\Services\ParsingService\OLX;

use App\Services\ParsingService\Enum\SiteType;

final readonly class ParseService
{
    public function __construct(
        private OlxRepositoryInterface $repository,
        private OlxClientInterface     $client,
    ) {}


}
