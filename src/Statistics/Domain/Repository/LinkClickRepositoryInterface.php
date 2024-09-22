<?php

declare(strict_types=1);

namespace App\Statistics\Domain\Repository;

use App\Statistics\Domain\Entity\LinkClick;

interface LinkClickRepositoryInterface
{
    public function save(LinkClick $linkClick): void;

    /**
     * @return LinkClick[]
     */
    public function getByCriteria(array $criteria): array;
}
