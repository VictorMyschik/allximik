<?php

namespace App\Services\ParsingService;

use App\Models\Link;
use App\Services\ParsingService\Enum\SiteType;
use stdClass;

interface LinkRepositoryInterface
{
    public function createLink(SiteType $type, string $path, string $query, string $hash): int;

    public function getByHash(string $hash): ?stdClass;

    public function upsertUserLink(string $user, int $linkId): void;

    /**
     * @return Link[]
     */
    public function getLinks(): array;

    public function getLinkById(int $linkId): Link;

    public function getUserIdsByLinkId(int $linkId): array;
}
