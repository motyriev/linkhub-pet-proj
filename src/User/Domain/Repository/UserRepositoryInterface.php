<?php

declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\User\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function create(User $user): void;

    public function findById(string $id): User;

    public function findByUsername(string $username): ?User;
}
