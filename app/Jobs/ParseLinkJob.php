<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Jobs\Enums\QueueJobEnum;
use App\Services\ParsingService\RunnerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class ParseLinkJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public function __construct(public int $id, public bool $withNotify)
    {
        $this->queue = QueueJobEnum::DEFAULT->value;
    }

    public function handle(RunnerService $service): void
    {
        $service->parseOffersByLink($this->id, $this->withNotify);
    }
}

