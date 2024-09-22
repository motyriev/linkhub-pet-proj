<?php

declare(strict_types=1);

namespace App\Statistics\Domain\Factory;

use App\Shared\Domain\Service\IdGenerator;
use App\Statistics\Domain\Entity\LinkClick;

class LinkClickFactory
{
    public function create(string $linkId, string $pageId, \DateTimeImmutable $dateTime, string $userAgent = ''): LinkClick
    {
        $linkClick = new LinkClick($linkId, $pageId, $dateTime, $userAgent);

        $linkClick->setId(IdGenerator::generate());

        return $linkClick;
    }
}
