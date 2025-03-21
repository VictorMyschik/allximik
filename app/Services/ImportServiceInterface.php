<?php

namespace App\Services;

interface ImportServiceInterface
{
    public function import(string $rawUrl, string $user): void;
}
