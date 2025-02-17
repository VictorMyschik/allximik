<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Database\DatabaseManager;

class RepositoryBase
{
    public function __construct(
        protected DatabaseManager $db
    ) {}
}
