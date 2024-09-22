<?php

declare(strict_types=1);

namespace App\Statistics\Application\DTO\PageVisit;

readonly class PageVisitDTO
{
    public function __construct(
        public string $id,
        public string $pageId,
        public \DateTimeImmutable $timestamp,
        public string $userAgent,
    ) {
    }
}
