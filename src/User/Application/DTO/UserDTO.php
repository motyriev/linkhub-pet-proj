<?php

declare(strict_types=1);

namespace App\User\Application\DTO;

use App\User\Domain\Entity\User;

class UserDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $email,
        public readonly string $username,
    ) {
    }

    public static function fromEntity(User $user): self
    {
        return new self(
            $user->getId(),
            $user->getEmail()->getValue(),
            $user->getUsername()
        );
    }
}
