<?php

declare(strict_types=1);

namespace App\Statistics\Application\UseCase\Command\CreateLinkClick;

use App\Shared\Application\Command\AbstractCommand;

readonly class CreateLinkClickCommand extends AbstractCommand
{
    public function __construct(
        public string $linkId,
        public string $pageId,
        public string $timestamp,
        public string $userAgent,
    ) {
    }
}
