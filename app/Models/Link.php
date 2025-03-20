<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\ORM\ORM;
use App\Services\ParsingService\Enum\SiteType;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Link extends ORM
{
    use AsSource;
    use Filterable;

    protected $table = 'links';

    public function getType(): SiteType
    {
        return SiteType::from($this->type);
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
