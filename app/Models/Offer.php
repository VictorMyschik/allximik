<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\ORM\ORM;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Offer extends ORM
{
    use AsSource;
    use Filterable;

    protected $table = 'offers';
    protected array $allowedSorts = [
        'id',
    ];

    public function getLink(): Link
    {
        return Link::loadByOrDie($this->link_id);
    }

    public function getSl(): string
    {
        return $this->sl;
    }
}
