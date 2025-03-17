<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Link;
use App\Services\ParsingService\Enum\SiteType;
use App\Services\ParsingService\LinkRepositoryInterface;

final readonly class LinkRepository extends DatabaseRepository implements LinkRepositoryInterface
{
    public function createLink(string $user, SiteType $type, string $path, string $query): int
    {
        return $this->db->table(Link::getTableName())->insertGetId([
            'user'  => $user,
            'type'  => $type->value,
            'path'  => $path,
            'query' => $query,
        ]);
    }

    public function getLinks(): array
    {
        return $this->db->table(Link::getTableName())->get()->toArray();
    }

    public function getLinkById(int $linkId): Link
    {
        return Link::loadByOrDie($linkId);
    }
}
