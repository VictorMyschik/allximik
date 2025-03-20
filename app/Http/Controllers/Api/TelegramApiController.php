<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Services\ImportService;
use App\Services\Telegram\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

final class TelegramApiController
{
    public function __construct(
        private readonly ImportService   $service,
        private readonly TelegramService $telegramService,
    ) {}

    public function index(Request $request): void
    {
        $body = $request->all();
        Log::info(json_encode($body));
        $message = (string)$body['message']['text'];
        $user = (string)$body['message']['chat']['id'];

        $result = $this->telegramService->manageBot($user, $message);

        if (!$result) {
            $this->service->import(url: $user, user: $message);
        }
    }
}
