<?php

namespace App\Console\Commands;

use App\Helpers\System\MrDateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class purge extends Command
{
  use ConsoleMessagesTrait;

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'purge';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Sync database, clear cache, clear logs';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   */
  public function handle(): void
  {
    if (ENV('PRODUCTION') === true) {
      $this->err('ERROR: USING FOR DEVELOPMENT ONLY');
      $this->nl();

      return;
    }

    $this->refreshTables();
    $this->addTablesData();

    $this->clearCache();

    $this->nl();
    $this->success('Data inserted: ' . MrDateTime::getTimeResult()[0]);
    $this->nl();
    $this->success('FINISH');
    $this->nl();
  }

  private function clearCache(): void
  {
    array_map('unlink', array_filter((array)glob('./storage/logs/*.log')));
    $this->success('Logs: cleared');
    $this->nl();

    Artisan::call('cache:clear');
    $this->success('Cache: cleared');
    $this->nl();

    Artisan::call('view:clear');
    $this->success('View: cleared');
    $this->nl();

    Artisan::call('route:clear');
    $this->success('Route: cleared');
    $this->nl();

    Artisan::call('config:clear');
    $this->success('Config: cleared');
    $this->nl();

    //echo exec('composer dump-autoload --optimize');
  }

  private function refreshTables(): void
  {
    $statements = [
      "DROP SCHEMA IF EXISTS public CASCADE;",
      "CREATE SCHEMA public;",
      "GRANT ALL ON SCHEMA public TO public;",
    ];

    foreach ($statements as $statement) {
      $result = DB::statement($statement);
      $result ? $this->success($statement) : $this->err($statement);
      $this->nl();
    }

    $this->nl();
    $this->success('migration tables');
    Artisan::call('migrate');
    $this->success(' is OK');
    $this->nl();
  }

  private function addTablesData(): void
  {
    MrDateTime::Start();

    $this->nl();
    $this->success('Add data');
    $this->nl();

    foreach ($this->tableList as $tableName) {

      $path = __DIR__ . "/purge_data/$tableName.sql";
      if (!is_file($path)) {
        continue;
      }

      $file = file_get_contents($path);

      if (strlen($file) < 5) {
        continue;
      }

      DB::unprepared($file);

      // Refresh field counter. for postgresql only
      DB::statement("SELECT pg_catalog.setval(pg_get_serial_sequence('$tableName', 'id'), MAX(id)) FROM $tableName;");
    }

    MrDateTime::StopItem();
  }

  private array $tableList = [
    'users',
    'country',
    'currency',
    'measure',
    'hike_type',
    'hike',
  ];
}
