<?php

declare(strict_types=1);

namespace App\User\Domain\Entity;

use App\Shared\Domain\Security\AuthUserInterface;
use App\Shared\Domain\Service\IdGenerator;
use App\User\Domain\Service\PasswordHasherInterface;
use App\User\Domain\ValueObject\Email;

class User implements AuthUserInterface
{
    private string $id;
    private string $password;

    public function __construct(
        private Email $email,
        private string $username,
    ) {
        $this->id = IdGenerator::generate();
    }

    public function setPassword(string $password, PasswordHasherInterface $hasher): void
    {
        $this->password = $hasher->hash($this, $password);
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function eraseCredentials(): void
    {
    }
}
