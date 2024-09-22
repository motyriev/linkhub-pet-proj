<?php

declare(strict_types=1);

namespace App\Tests\Resources\Fixture\Custom;

use App\Statistics\Domain\Factory\PageVisitFactory;
use App\Statistics\Domain\Repository\PageVisitRepositoryInterface;
use App\Tests\Resources\FakerTrait;

class ClickHousePageVisitFixture
{
    use FakerTrait;

    public function __construct(private PageVisitRepositoryInterface $repository)
    {
    }

    public function load(string $pageId): void
    {
        $visitCount = $this->getFaker()->numberBetween(3, 8);

        for ($i = 0; $i < $visitCount; ++$i) {
            $timestamp = new \DateTimeImmutable();
            $userAgent = $this->getFaker()->userAgent();

            $pageVisit = (new PageVisitFactory())->create($pageId, $timestamp, $userAgent);

            $this->repository->save($pageVisit);
        }
    }
}
