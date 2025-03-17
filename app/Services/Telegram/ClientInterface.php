<?php

namespace App\Services\Telegram;

interface ClientInterface
{
    public function sendMessage(string $userId, string $message): void;

    public function setWebHook(): array;
}
