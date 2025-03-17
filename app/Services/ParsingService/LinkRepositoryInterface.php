<?php

namespace App\Services\ParsingService;

use App\Services\ParsingService\Enum\SiteType;

interface LinkRepositoryInterface
{
    public function createLink(string $user, SiteType $type, string $path, string $query);
}
