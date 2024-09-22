<?php

declare(strict_types=1);

namespace App\Statistics\Domain\Repository;

use App\Statistics\Domain\Entity\PageVisit;

interface PageVisitRepositoryInterface
{
    public function save(PageVisit $pageVisit): void;

    /**
     * @return PageVisit[]
     */
    public function getByCriteria(array $criteria): array;
}
