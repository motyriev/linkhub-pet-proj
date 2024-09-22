<?php

declare(strict_types=1);

namespace App\User\Domain\Factory;

use App\User\Domain\Entity\User;
use App\User\Domain\Service\PasswordHasherInterface;
use App\User\Domain\ValueObject\Email;

class UserFactory
{
    public function __construct(private readonly PasswordHasherInterface $hasher)
    {
    }

    public function create(string $email, string $username, string $password): User
    {
        $emailVO = new Email($email);

        $user = new User($emailVO, $username);
        $user->setPassword($password, $this->hasher);

        return $user;
    }
}
