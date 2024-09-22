<?php

declare(strict_types=1);

namespace App\Statistics\Application\DTO\LinkClick;

use App\Statistics\Domain\Entity\LinkClick;

class LinkClickDTOTransformer
{
    public function fromEntity(LinkClick $linkClick): LinkClickDTO
    {
        return new LinkClickDTO(
            id: $linkClick->getId(),
            linkId: $linkClick->getLinkId(),
            pageId: $linkClick->getPageId(),
            timestamp: $linkClick->getTimestamp(),
            userAgent: $linkClick->getUserAgent(),
        );
    }

    public function fromEntities(array $linkClicks): array
    {
        return array_map([$this, 'fromEntity'], $linkClicks);
    }
}
