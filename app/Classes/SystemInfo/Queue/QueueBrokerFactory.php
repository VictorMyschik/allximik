<?php

namespace App\Classes\SystemInfo\Queue;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Config\Repository;
use Illuminate\Support\Facades\DB;

readonly class QueueBrokerFactory
{
    public function __construct(private Repository $config) {}

    /**
     * @throws Exception
     */
    public function getBroker(): QueueBrokerInterface
    {
        switch ($this->config->get('queue.default')) {
            case 'rabbitmq':
                $rabbitConfig = $this->config->get('queue.connections.rabbitmq');

                $baseUrl = $rabbitConfig['host'] . ':1' . $rabbitConfig['port'];

                $client = new Client([
                    'auth' => [$rabbitConfig['login'], $rabbitConfig['password']],
                ]);

                return new RabbitMQBroker($client, $baseUrl);
            case 'database':
                return new DatabaseBroker(DB::connection());
            default:
                throw new Exception('Unexpected value for connect to Queue Broker');
        }
    }
}
