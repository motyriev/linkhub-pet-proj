<?php

declare(strict_types=1);

namespace App\UserPage\Application\UseCase\Query\FindPageByUsername;

use App\Shared\Application\Query\QueryHandlerInterface;
use App\UserPage\Application\DTO\PageDTO;
use App\UserPage\Domain\Repository\PageRepositoryInterface;
use Webmozart\Assert\Assert;

readonly class FindPageByUsernameQueryHandler implements QueryHandlerInterface
{
    public function __construct(private PageRepositoryInterface $pageRepo)
    {
    }

    public function __invoke(FindPageByUsernameQuery $q): PageDTO
    {
        Assert::notEmpty($q->username, 'username cannot be empty');

        $page = $this->pageRepo->findByUsername($q->username);

        Assert::notNull($page, 'Page not found');

        return PageDTO::fromEntity($page);
    }
}
