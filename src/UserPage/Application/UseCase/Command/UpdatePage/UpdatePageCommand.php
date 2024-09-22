<?php

declare(strict_types=1);

namespace App\UserPage\Application\UseCase\Command\UpdatePage;

use App\Shared\Application\Command\AbstractCommand;

readonly class UpdatePageCommand extends AbstractCommand
{
    public function __construct(
        public string $pageId,
        public string $description,
        public array $linksData,
    ) {
    }
}
