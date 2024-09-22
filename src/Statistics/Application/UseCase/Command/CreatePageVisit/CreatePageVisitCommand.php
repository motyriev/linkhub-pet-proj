<?php

declare(strict_types=1);

namespace App\Statistics\Application\UseCase\Command\CreatePageVisit;

use App\Shared\Application\Command\AbstractCommand;

readonly class CreatePageVisitCommand extends AbstractCommand
{
    public function __construct(
        public string $pageId,
        public string $timestamp,
        public string $userAgent,
    ) {
    }
}
