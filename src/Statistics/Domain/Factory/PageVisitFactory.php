<?php

declare(strict_types=1);

namespace App\Statistics\Domain\Factory;

use App\Shared\Domain\Service\IdGenerator;
use App\Statistics\Domain\Entity\PageVisit;

class PageVisitFactory
{
    public function create(string $pageId, \DateTimeImmutable $dateTime, string $userAgent = ''): PageVisit
    {
        $pageVisit = new PageVisit($pageId, $dateTime, $userAgent);

        $pageVisit->setId(IdGenerator::generate());

        return $pageVisit;
    }
}
