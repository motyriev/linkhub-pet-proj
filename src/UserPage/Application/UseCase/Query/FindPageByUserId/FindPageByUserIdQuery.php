<?php

declare(strict_types=1);

namespace App\UserPage\Application\UseCase\Query\FindPageByUserId;

use App\Shared\Application\Query\QueryInterface;

readonly class FindPageByUserIdQuery implements QueryInterface
{
    public function __construct(public string $userId)
    {
    }
}
