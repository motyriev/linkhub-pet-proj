<?php

declare(strict_types=1);

namespace App\User\Application\UseCase\Query\FindUserByUsername;

use App\Shared\Application\Query\QueryInterface;

class FindUserByUsernameQuery implements QueryInterface
{
    public function __construct(public readonly string $username)
    {
    }
}
