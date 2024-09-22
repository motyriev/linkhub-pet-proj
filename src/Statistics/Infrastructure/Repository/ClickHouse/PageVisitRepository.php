<?php

declare(strict_types=1);

namespace App\Statistics\Infrastructure\Repository\ClickHouse;

use App\Statistics\Domain\Entity\PageVisit;
use App\Statistics\Domain\Repository\PageVisitRepositoryInterface;
use App\Statistics\Infrastructure\Service\ClickHouseClientFactory;
use ClickhouseClient\Client\Client;

class PageVisitRepository implements PageVisitRepositoryInterface
{
    private Client $client;
    private const TABLE = 'statistics_page_visits';

    public function __construct(ClickHouseClientFactory $clientFactory)
    {
        $this->client = $clientFactory->create();
    }

    public function save(PageVisit $pageVisit): void
    {
        $sql = sprintf(
            "INSERT INTO %s (id, page_id, timestamp, user_agent) VALUES ('%s', '%s', '%s', '%s')",
            self::TABLE,
            $pageVisit->getId(),
            $pageVisit->getPageId(),
            $pageVisit->getTimestamp()->format('Y-m-d H:i:s'),
            $pageVisit->getUserAgent()
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
            $pageVisit = new PageVisit(
                $row['page_id'],
                new \DateTimeImmutable($row['timestamp']),
                $row['user_agent']
            );

            $pageVisit->setId($row['id']);

            return $pageVisit;
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
