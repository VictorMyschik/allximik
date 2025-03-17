<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Services\ImportService;
use Illuminate\Http\Request;

class TelegramApiController
{
    public function __construct(private readonly ImportService $service) {}

    public function index(Request $request): void
    {
        $body = $request->all();

        $this->service->import(
            url: (string)$body['message']['text'],
            user: (string)$body['message']['chat']['id']
        );
    }
}
