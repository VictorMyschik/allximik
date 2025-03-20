<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\ORM\ORM;

class UserLink extends ORM
{
    protected $table = 'user_links';

    public function getLink(): Link
    {
        return Link::loadByOrDie($this->link_id);
    }

    public function getLinkId(): int
    {
        return $this->link_id;
    }
}
