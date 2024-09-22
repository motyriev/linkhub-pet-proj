<?php

declare(strict_types=1);

namespace App\Statistics\Infrastructure\Repository\ClickHouse;

use App\Statistics\Domain\Entity\LinkClick;
use App\Statistics\Domain\Repository\LinkClickRepositoryInterface;
use App\Statistics\Infrastructure\Service\ClickHouseClientFactory;
use ClickhouseClient\Client\Client;

class LinkClickRepository implements LinkClickRepositoryInterface
{
    private Client $client;
    private const TABLE = 'statistics_link_clicks';

    public function __construct(ClickHouseClientFactory $clientFactory)
    {
        $this->client = $clientFactory->create();
    }

    public function save(LinkClick $linkClick): void
    {
        $sql = sprintf(
            "INSERT INTO %s (id, link_id, page_id, timestamp, user_agent) VALUES ('%s', '%s', '%s', '%s', '%s')",
            self::TABLE,
            $linkClick->getId(),
            $linkClick->getLinkId(),
            $linkClick->getPageId(),
            $linkClick->getTimestamp()->format('Y-m-d H:i:s'),
            $linkClick->getUserAgent()
        );

        $this->client->write($sql);
    }

    public function getByCriteria(array $criteria): array
    {
        $sql = 'SELECT * FROM '.self::TABLE.' WHERE ';
        $conditions = [];

        foreach ($criteria as $field => $value) {
            if (!empty($value)) {
                $escapedValue = $this->escapeValue($value);
                $conditions[] = "$field = '$escapedValue'";
            } else {
                $conditions[] = "$field IS NULL";
            }
        }

        $sql .= implode(' AND ', $conditions);

        $response = $this->client->query($sql);

        $rows = $response->getContent()['data'];

        return array_map(function ($row) {
            $linkClick = new LinkClick(
                $row['link_id'],
                $row['page_id'],
                new \DateTimeImmutable($row['timestamp']),
                $row['user_agent']
            );

            $linkClick->setId($row['id']);

            return $linkClick;
        }, $rows);
    }

    private function escapeValue($value): string
    {
        if (is_string($value)) {
            return str_replace("'", "''", $value);
        }

        return (string) $value;
    }
}
