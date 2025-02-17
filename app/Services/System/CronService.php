<?php

declare(strict_types=1);

namespace App\Services\System;

use App\Classes\Cron\CurrencyRateService;
use App\Models\System\Cron;
use App\Orchid\Screens\System\Enum\CronKeyEnum;
use DateInterval;
use Exception;
use Illuminate\Support\Facades\Log;

final readonly class CronService
{
    public function __construct(private CurrencyRateService $currencyRateService) {}

    public function setLog(string $message): void
    {
        Log::info($message);
    }

    public function runAllActive(): void
    {
        /** @var Cron $job */
        foreach (Cron::where('active', true)->get()->all() as $job) {
            if ($this->needRun($job)) {
                try {
                    $this->run($job);
                } catch (Exception $e) {
                    $this->setLog('Wrong run cron job:' . $e->getMessage());
                }
            }
        }
    }

    public function needRun(Cron $job): bool
    {
        $lastWork = $job->getLastWork();
        if (is_null($lastWork)) {
            return true;
        }
        $lastWork->add(new DateInterval('PT' . $job->getPeriod() . 'M'));

        return now() > $lastWork;
    }

    public function run(Cron $cron): void
    {
        match ($cron->getCronKey()) {
            CronKeyEnum::CurrencyRate => $this->currencyRateService->update(),
            default => throw new Exception('Unknown cron key'),
        };

        $cron->setLastWork(now());
        $cron->save();
    }
}
