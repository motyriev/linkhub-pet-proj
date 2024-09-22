<?php

declare(strict_types=1);

namespace App\UserPage\Application\UseCase\Query\FindPageByUserId;

use App\Shared\Application\Query\QueryHandlerInterface;
use App\UserPage\Application\DTO\PageDTO;
use App\UserPage\Domain\Repository\PageRepositoryInterface;
use Webmozart\Assert\Assert;

readonly class FindPageByUserIdQueryHandler implements QueryHandlerInterface
{
    public function __construct(private PageRepositoryInterface $pageRepo)
    {
    }

    public function __invoke(FindPageByUserIdQuery $q): PageDTO
    {
        Assert::notEmpty($q->userId, 'userId cannot be empty');

        $page = $this->pageRepo->findByUserId($q->userId);

        Assert::notNull($page, 'Page not found');

        return PageDTO::fromEntity($page);
    }
}
