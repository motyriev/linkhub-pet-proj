<?php

declare(strict_types=1);

namespace App\User\Application\UseCase\Query\FindUserByUsername;

use App\Shared\Application\Query\QueryHandlerInterface;
use App\User\Application\DTO\UserDTO;
use App\User\Domain\Repository\UserRepositoryInterface;
use Webmozart\Assert\Assert;

class FindUserByUsernameQueryHandler implements QueryHandlerInterface
{
    public function __construct(private readonly UserRepositoryInterface $userRepo)
    {
    }

    public function __invoke(FindUserByUsernameQuery $q): UserDTO
    {
        Assert::notEmpty($q->username, 'username cannot be empty');

        $user = $this->userRepo->findByUsername($q->username);

        if (!$user) {
            throw new \Exception('User not found: '.$q->username);
        }

        return UserDTO::fromEntity($user);
    }
}
