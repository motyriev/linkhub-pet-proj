<?php

declare(strict_types=1);

namespace App\Statistics\Application\UseCase\Command\CreatePageVisit;

use App\Shared\Application\Command\AbstractCommandHandler;
use App\Statistics\Domain\Factory\PageVisitFactory;
use App\Statistics\Domain\Repository\PageVisitRepositoryInterface;
use Webmozart\Assert\Assert;

readonly class CreatePageVisitCommandHandler extends AbstractCommandHandler
{
    public function __construct(
        private PageVisitRepositoryInterface $pageVisitRepository,
        private PageVisitFactory $pageVisitFactory,
    ) {
    }

    public function __invoke(CreatePageVisitCommand $c): bool
    {
        Assert::notEmpty($c->timestamp, 'timestamp cannot be empty');
        Assert::notEmpty($c->pageId, 'pageId cannot be empty');
        $timestamp = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $c->timestamp);

        Assert::lessThanEq($timestamp, new \DateTimeImmutable(), 'timestamp cannot be in the future');

        $pageVisit = $this->pageVisitFactory->create($c->pageId, $timestamp, $c->userAgent);
        $this->pageVisitRepository->save($pageVisit);

        return true;
    }
}
