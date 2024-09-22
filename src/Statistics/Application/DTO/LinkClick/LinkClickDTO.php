<?php

declare(strict_types=1);

namespace App\Statistics\Application\DTO\LinkClick;

readonly class LinkClickDTO
{
    public function __construct(
        public string $id,
        public string $linkId,
        public string $pageId,
        public \DateTimeImmutable $timestamp,
        public string $userAgent,
    ) {
    }
}
