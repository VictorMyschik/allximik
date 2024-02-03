<?php

namespace App\Classes\Cron;

use App\Jobs\CurrencyRateJob;
use App\Models\Cron;
use DateInterval;
use Exception;
use Illuminate\Support\Facades\Log;
use Psr\Log\LogLevel;

class CronBase
{
  private const CHANEL = 'cron';

  public function setLog(string $message): void
  {
    Log::channel(self::CHANEL)->log(LogLevel::INFO, $message);
  }

  public function needRun(Cron $job): bool
  {
    $lastWork = $job->getLastWorkObject();
    $lastWork->add(new DateInterval('PT' . $job->getPeriod() . 'M'));

    return now() > $lastWork;
  }

  public function runAllActive(): void
  {
    /** @var Cron $list */
    $list = Cron::where('active', true)->get();
    foreach ($list as $job) {
      if ($this->needRun($job)) {
        try {
          $this->run($job);
        } catch (Exception $e) {
          $this->setLog('Wrong run cron job:' . $e->getMessage());
        }
      }
    }
  }

  /**
   * @throws Exception
   */
  public function run(Cron $cron): void
  {
    switch ($cron->getCronKey()) {
      case 'currency_rate':
        CurrencyRateJob::dispatch();

        break;
      default:
        throw new Exception('Unknown cron key: ID' . $cron->id() . ' key: ' . $cron->getCronKey());
    }

    $cron->setLastWork(now());
    $cron->save_mr();
  }
}
