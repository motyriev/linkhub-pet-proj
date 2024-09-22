<?php

declare(strict_types=1);

namespace App\Statistics\Infrastructure\Service;

use ClickhouseClient\Client\Client;
use ClickhouseClient\Client\Config;

readonly class ClickHouseClientFactory
{
    public function __construct(
        private string $host,
        private string $port,
        private string $user,
        private string $password,
        private string $database,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function create(): Client
    {
        $config = new Config(
            ['host' => $this->host, 'port' => $this->port, 'protocol' => 'http'],
            ['database' => $this->database],
            ['user' => $this->user, 'password' => $this->password]
        );

        return new Client($config);
    }
}
