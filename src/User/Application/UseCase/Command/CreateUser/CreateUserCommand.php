<?php

declare(strict_types=1);

namespace App\User\Application\UseCase\Command\CreateUser;

use App\Shared\Application\Command\AbstractCommand;

readonly class CreateUserCommand extends AbstractCommand
{
    public function __construct(
        public readonly string $email,
        public readonly string $username,
        public readonly string $password,
    ) {
    }
}
