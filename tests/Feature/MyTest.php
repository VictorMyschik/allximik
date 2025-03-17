<?php

namespace Tests\Feature;

use App\Services\ParsingService\RunnerService;
use Tests\TestCase;

class MyTest extends TestCase
{
    public function testParse(): void
    {
        /** @var RunnerService $service */
        $service = app(RunnerService::class);
        $service->parseByLink(1);
    }
}
