<?php

declare(strict_types=1);

namespace App\User\Application\UseCase\Command\CreateUser;

use App\Shared\Application\Command\AbstractCommandHandler;
use App\User\Domain\Factory\UserFactory;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\ValueObject\Email;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Webmozart\Assert\Assert;

readonly class CreateUserCommandHandler extends AbstractCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserFactory $userFactory,
    ) {
    }

    public function __invoke(CreateUserCommand $c): string
    {
        Assert::notEmpty($c->email, 'email cannot be empty');
        Assert::notEmpty($c->username, 'username cannot be empty');
        Assert::notEmpty($c->password, 'password cannot be empty');

        $email = new Email($c->email);

        if ($this->userRepository->emailExists($email)) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Email is already taken');
        }

        $user = $this->userFactory->create(
            email: $c->email,
            username: $c->username,
            password: $c->password,
        );

        $this->userRepository->create($user);

        return $user->getId();
    }
}
