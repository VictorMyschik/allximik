<?php

namespace App\Repositories;


use App\Services\ParsingService\OLX\OlxRepositoryInterface;

final readonly class OlxRepository extends DatabaseRepository implements OlxRepositoryInterface
{
    public function saveOffer(): int
    {
       return 1;
    }
}
