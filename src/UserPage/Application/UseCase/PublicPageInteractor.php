<?php

declare(strict_types=1);

namespace App\UserPage\Application\UseCase;

use App\Shared\Application\Query\QueryBusInterface;
use App\UserPage\Application\DTO\PageDTO;
use App\UserPage\Application\UseCase\Query\FindPageByUsername\FindPageByUsernameQuery;

readonly class PublicPageInteractor
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {
    }

    public function getPublicPageByUsername(string $username): PageDTO
    {
        return $this->queryBus->execute(new FindPageByUsernameQuery($username));
    }
}
