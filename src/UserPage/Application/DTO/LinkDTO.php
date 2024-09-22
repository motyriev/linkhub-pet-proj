<?php

declare(strict_types=1);

namespace App\UserPage\Application\DTO;

use App\UserPage\Domain\Entity\Link;

class LinkDTO
{
    public function __construct(
        public string $id,
        public string $title,
        public string $url,
    ) {
    }

    public static function fromEntity(Link $link): self
    {
        return new self(
            id: $link->getId(),
            title: $link->getTitle(),
            url: $link->getUrl(),
        );
    }
}
