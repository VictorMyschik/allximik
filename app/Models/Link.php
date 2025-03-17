<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\ORM\ORM;
use App\Services\ParsingService\Enum\SiteType;

class Link extends ORM
{
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
