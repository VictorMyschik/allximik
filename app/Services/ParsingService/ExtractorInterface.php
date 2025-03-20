<?php

namespace App\Services\ParsingService;

interface ExtractorInterface
{
    public function getTitle(): string;

    public function getPrice(): string;

    public function getPhoto(): string;

    public function getLink(): string;

    public function getParameter(string $key): string;
}
