<?php

namespace App\Classes\SystemInfo\Queue;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

readonly class RabbitMQBroker extends AbstractQueueBroker
{
    private array $queueList;

    public function __construct(
        private Client $client,
        private string $baseUrl
    ) {}

    public function getOverview(): array
    {
        $response = $this->client->get($this->baseUrl . '/api/overview');

        return json_decode($response->getBody(), true);
    }

    public function getVersion(): string
    {
        return $this->getOverview()['product_version'];
    }

    public function getQueueList(): array
    {
        $response = $this->client->get($this->baseUrl . '/api/queues');
        $this->queueList = json_decode($response->getBody(), true);

        return array_column($this->queueList, 'name');
    }

    public function getJobsCountByQueue(string $queue): int
    {
        if (count($this->queueList) === 0) {
            $this->getQueueList();
        }

        foreach ($this->queueList as $item) {
            if ($item['name'] !== $queue) {
                continue;
            }

            return (int) $item['messages_ready'];
        }

        return 0;
    }

    /**
     * @throws GuzzleException
     */
    public function purge(string $queue): void
    {
        $this->client->delete($this->baseUrl . "/api/queues/%2F/{$queue}/contents");
    }

    /**
     * @throws GuzzleException
     */
    public function delete(string $queue): void
    {
        $this->client->delete($this->baseUrl . "/api/queues/%2F/{$queue}");
    }

    public function getQueueBrokerName(): string
    {
        return 'RabbitMQ';
    }
}
