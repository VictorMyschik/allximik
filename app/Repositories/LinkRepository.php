<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Link;
use App\Models\UserLink;
use App\Services\ParsingService\Enum\SiteType;
use App\Services\ParsingService\LinkRepositoryInterface;

final readonly class LinkRepository extends DatabaseRepository implements LinkRepositoryInterface
{
    public function createLink(SiteType $type, string $path, string $query, string $hash): int
    {
        return $this->db->table(Link::getTableName())
            ->insertGetId([
                'hash'  => $hash,
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

    public function getByHash(string $hash): ?\stdClass
    {
        return $this->db->table(Link::getTableName())->where('hash', $hash)->first();
    }

    public function upsertUserLink(string $user, int $linkId): void
    {
        $this->db->table(UserLink::getTableName())
            ->where('id', $linkId)
            ->updateOrInsert([
                'user'    => $user,
                'link_id' => $linkId,
            ]);
    }

    public function getUserIdsByLinkId(int $linkId): array
    {
        return $this->db->table(UserLink::getTableName())
            ->where('link_id', $linkId)
            ->pluck('user')
            ->toArray();
    }
}
