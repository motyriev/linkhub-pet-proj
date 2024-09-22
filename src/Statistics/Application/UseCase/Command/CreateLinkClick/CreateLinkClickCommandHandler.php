<?php

declare(strict_types=1);

namespace App\Statistics\Application\UseCase\Command\CreateLinkClick;

use App\Shared\Application\Command\AbstractCommandHandler;
use App\Statistics\Domain\Factory\LinkClickFactory;
use App\Statistics\Domain\Repository\LinkClickRepositoryInterface;
use Webmozart\Assert\Assert;

readonly class CreateLinkClickCommandHandler extends AbstractCommandHandler
{
    public function __construct(
        private LinkClickRepositoryInterface $linkClickRepo,
        private LinkClickFactory $linkClickFactory,
    ) {
    }

    public function __invoke(CreateLinkClickCommand $c): bool
    {
        Assert::notEmpty($c->linkId, 'linkId cannot be empty');
        Assert::notEmpty($c->pageId, 'pageId cannot be empty');
        Assert::notEmpty($c->timestamp, 'timestamp cannot be empty');

        $timestamp = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $c->timestamp);

        Assert::lessThanEq($timestamp, new \DateTimeImmutable(), 'timestamp canot be in the future');

        $linkClick = $this->linkClickFactory->create($c->linkId, $c->pageId, $timestamp, $c->userAgent);
        $this->linkClickRepo->save($linkClick);

        return true;
    }
}
