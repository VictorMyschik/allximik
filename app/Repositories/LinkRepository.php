<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Link;
use App\Services\ParsingService\Enum\SiteType;
use App\Services\ParsingService\LinkRepositoryInterface;

final readonly class LinkRepository extends DatabaseRepository implements LinkRepositoryInterface
{
    public function createLink(string $user, SiteType $type, string $path, string $query)
    {
        return $this->db->table(Link::getTableName())->insertGetId([
            'user'  => $user,
            'type'  => $type->value,
            'path'  => $path,
            'query' => $query,
        ]);
    }
}
