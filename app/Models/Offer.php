<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\ORM\ORM;
use App\Services\ParsingService\Enum\SiteType;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Offer extends ORM
{
    use AsSource;
    use Filterable;

    protected $table = 'offers';
    protected array $allowedSorts = [
        'id',
        'offer_id',
        'link_id',
        'created_at',
        'updated_at',
    ];

    public function getType(): SiteType
    {
        return SiteType::from($this->type);
    }

    public function getSl(): string
    {
        return $this->sl;
    }
}
