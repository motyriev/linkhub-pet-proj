<?php

declare(strict_types=1);

namespace App\Statistics\Application\DTO\PageVisit;

use App\Statistics\Domain\Entity\PageVisit;

class PageVisitDTOTransformer
{
    public function fromEntity(PageVisit $pageVisit): PageVisitDTO
    {
        return new PageVisitDTO(
            id: $pageVisit->getId(),
            pageId: $pageVisit->getPageId(),
            timestamp: $pageVisit->getTimestamp(),
            userAgent: $pageVisit->getUserAgent(),
        );
    }

    public function fromEntities(array $pageVisits): array
    {
        return array_map([$this, 'fromEntity'], $pageVisits);
    }
}
