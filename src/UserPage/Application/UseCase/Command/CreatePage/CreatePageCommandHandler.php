<?php

declare(strict_types=1);

namespace App\UserPage\Application\UseCase\Command\CreatePage;

use App\Shared\Application\Command\AbstractCommandHandler;
use App\UserPage\Domain\Factory\PageFactory;
use App\UserPage\Domain\Repository\PageRepositoryInterface;

readonly class CreatePageCommandHandler extends AbstractCommandHandler
{
    public function __construct(
        private PageFactory $pageFactory,
        private PageRepositoryInterface $pageRepository,
    ) {
    }

    public function __invoke(CreatePageCommand $c): string
    {
        $page = $this->pageFactory->create($c->user, $c->description);
        $this->pageRepository->save($page);

        return $page->getId();
    }
}
