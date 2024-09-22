<?php

declare(strict_types=1);

namespace App\Statistics\Domain\Entity;

class LinkClick
{
    private string $id;

    public function __construct(
        private string $linkId,
        private string $pageId,
        private \DateTimeImmutable $timestamp,
        private string $userAgent,
    ) {
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getLinkId(): string
    {
        return $this->linkId;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPageId(): string
    {
        return $this->pageId;
    }

    public function getTimestamp(): \DateTimeImmutable
    {
        return $this->timestamp;
    }

    public function getUserAgent(): string
    {
        return $this->userAgent;
    }
}
