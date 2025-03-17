<?php

declare(strict_types=1);

namespace App\Jobs\Enums;

use UnitEnum;

enum QueueJobEnum: string
{
    case DEFAULT = 'default'; // Мелкие задачи, mix
}
