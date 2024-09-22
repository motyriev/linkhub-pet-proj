<?php

declare(strict_types=1);

namespace App\UserPage\Application\UseCase\Query\FindPageByUsername;

use App\Shared\Application\Query\QueryInterface;

readonly class FindPageByUsernameQuery implements QueryInterface
{
    public function __construct(public string $username)
    {
    }
}
