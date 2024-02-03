<?php

namespace App\Classes\SystemInfo\Queue;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\DB;

readonly class DatabaseBroker extends AbstractQueueBroker
{
  public function __construct(
    private ConnectionInterface $connection
  ) {}

  public function getQueueBrokerName(): string
  {
    return 'Database';
  }

  public function getQueueList(): array
  {
    return $this->connection->table('jobs')->groupBy('queue')->pluck('queue')->toArray();
  }

  public function getJobsCountByQueue(string $queue): int
  {
    return $this->connection->table('jobs')->where('queue', $queue)->count();
  }

  public function getVersion(): string
  {
    return $this->connection->select(DB::raw('select version()'))[0]->version;
  }

  public function purge(string $queue): void
  {
    $this->connection->table('jobs')->where('queue', $queue)->delete();
  }

  public function delete(string $queue): void
  {
    $this->connection->table('jobs')->where('queue', $queue)->delete();
  }

  public function getOverview(): array
  {
    return [];
  }
}
