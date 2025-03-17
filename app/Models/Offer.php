<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\ORM\ORM;

class Offer extends ORM
{
    protected $table = 'offers';

    public function getLink(): Link
    {
        return Link::loadByOrDie($this->link_id);
    }

    public function getSl(): string
    {
        return $this->sl;
    }
}
