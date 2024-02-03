<?php

namespace App\Classes\SystemInfo\Queue;

interface QueueBrokerInterface
{
    public function getQueueBrokerName(): string;

    public function purge(string $queue): void;

    public function delete(string $queue): void;

    public function getJobsCountByQueue(string $queue): int;

    public function getOverview(): array;

    public function getVersion(): string;

    public function getQueueList(): array;

    public function getDisplayInfo(): array;
}
