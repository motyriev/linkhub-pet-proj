<?php

declare(strict_types=1);

namespace App\Statistics\Infrastructure\Database;

use App\Statistics\Infrastructure\Service\ClickHouseClientFactory;
use ClickhouseClient\Client\Client;

class ClickHouseMigration
{
    private Client $client;

    public function __construct(ClickHouseClientFactory $clientFactory)
    {
        $this->client = $clientFactory->create();
    }

    public function migrate(): void
    {
        $this->client->system(
            '
        CREATE TABLE IF NOT EXISTS statistics_page_visits
        (
            id          String,
            page_id     String,
            timestamp   DateTime,
            user_agent  Nullable(String)
        ) ENGINE = MergeTree()
        ORDER BY (page_id, timestamp)
    '
        );

        $this->client->system(
            '
        CREATE TABLE IF NOT EXISTS statistics_link_clicks
        (
            id          String,
            link_id     String,
            page_id     String,
            timestamp   DateTime,
            user_agent  Nullable(String)
        ) ENGINE = MergeTree()
        ORDER BY (link_id, timestamp)
    '
        );
    }

    public function rollback(): void
    {
        $this->client->system('DROP TABLE IF EXISTS statistics_page_visits');

        $this->client->system('DROP TABLE IF EXISTS statistics_link_clicks');
    }
}
