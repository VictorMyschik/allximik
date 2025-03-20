<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Link;
use App\Models\UserLink;
use App\Services\ParsingService\Enum\SiteType;
use App\Services\ParsingService\LinkRepositoryInterface;
use Illuminate\Support\Facades\DB;

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

    public function clearByUser(string $user): void
    {
        $ids = $this->db->table(UserLink::getTableName() . ' as ul1')
            ->join(UserLink::getTableName() . ' as ul2', 'ul1.link_id', '=', 'ul2.link_id')
            ->where('ul1.user', $user)
            ->groupBy('ul1.link_id')
            ->havingRaw('COUNT(ul2.link_id) = 1')
            ->pluck('ul1.link_id')
            ->toArray();

        $this->db->table(Link::getTableName())->whereIn('id', $ids)->delete();
        $this->db->table(UserLink::getTableName())->where('user', $user)->delete();
    }
}
