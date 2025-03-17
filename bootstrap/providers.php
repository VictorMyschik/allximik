<?php

use App\Providers\ClientsProvider;
use App\Providers\RepositoryProvider;

return [
    App\Providers\AppServiceProvider::class,
    ClientsProvider::class,
    RepositoryProvider::class,
];
