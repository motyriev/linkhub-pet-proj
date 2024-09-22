<?php

declare(strict_types=1);

namespace App\UserPage\Application\DTO;

use App\UserPage\Domain\Entity\Page;

class PageDTO
{
    public function __construct(
        public string $pageId,
        public string $username,
        public string $description,
        public array $links,
    ) {
    }

    public static function fromEntity(Page $page): self
    {
        return new self(
            pageId: $page->getId(),
            username: $page->getUser()->getUsername(),
            description: $page->getDescription(),
            links: array_map(fn ($link) => LinkDTO::fromEntity($link),
                $page->getLinks()->toArray())
        );
    }
}
