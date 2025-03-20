<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Services\ImportService;
use App\Services\Telegram\TelegramService;
use Illuminate\Http\Request;

final class TelegramApiController
{
    public function __construct(
        private readonly ImportService   $service,
        private readonly TelegramService $telegramService,
    ) {}

    public function index(Request $request): void
    {
        $body = $request->all();

        $message = (string)$body['message']['text'];
        $user = (string)$body['message']['chat']['id'];

        $this->telegramService->manageBot($user, $message);

        $this->service->import(url: $user, user: $message);
    }
}
