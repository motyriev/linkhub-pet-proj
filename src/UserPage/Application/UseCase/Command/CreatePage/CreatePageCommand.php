<?php

declare(strict_types=1);

namespace App\UserPage\Application\UseCase\Command\CreatePage;

use App\Shared\Application\Command\AbstractCommand;
use App\User\Domain\Entity\User;

readonly class CreatePageCommand extends AbstractCommand
{
    public function __construct(
        public User $user,
        public string $description,
    ) {
    }
}
