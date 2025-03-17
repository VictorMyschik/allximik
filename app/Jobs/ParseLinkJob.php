<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Jobs\Enums\QueueJobEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class ParseLinkJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public function __construct(public int $id)
    {
        $this->queue = QueueJobEnum::DEFAULT->value;
    }

    public function handle(): void
    {

    }
}

