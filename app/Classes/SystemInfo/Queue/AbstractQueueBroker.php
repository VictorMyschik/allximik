<?php

namespace App\Classes\SystemInfo\Queue;

abstract readonly class AbstractQueueBroker implements QueueBrokerInterface
{
    public function getDisplayInfo(): array
    {
        $list = $this->getQueueList();

        $out = [];

        $out['info'] = [
            'Name'        => $this->getQueueBrokerName(),
            'Version'     => $this->getVersion(),
            'Queue count' => count($list),
        ];

        $details = [];

        foreach ($list as $queue) {
            $details[$queue] = $this->getJobsCountByQueue($queue);
        }

        $out['details'] = $details;

        return $out;
    }
}
