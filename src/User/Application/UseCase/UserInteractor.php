<?php

declare(strict_types=1);

namespace App\User\Application\UseCase;

use App\Shared\Application\Command\CommandBusInterface;
use App\User\Application\UseCase\Command\CreateUser\CreateUserCommand;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\UserPage\Application\UseCase\Command\CreatePage\CreatePageCommand;

class UserInteractor
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function registerWithPage(string $email, string $username, string $password): void
    {
        // transaction
        $userId = $this->commandBus->execute(new CreateUserCommand($email, $username, $password));

        $user = $this->userRepository->findById($userId);

        $this->commandBus->execute(new CreatePageCommand($user, ''));
    }
}
