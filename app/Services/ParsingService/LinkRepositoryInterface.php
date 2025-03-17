<?php

namespace App\Services\ParsingService;

use App\Models\Link;
use App\Services\ParsingService\Enum\SiteType;

interface LinkRepositoryInterface
{
    public function createLink(string $user, SiteType $type, string $path, string $query);

    /**
     * @return Link[]
     */
    public function getLinks(): array;

    public function getLinkById(int $linkId): Link;
}
