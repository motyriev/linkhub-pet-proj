<?php

declare(strict_types=1);

namespace App\Statistics\Application\UseCase;

use App\Shared\Application\Command\CommandBusInterface;
use App\Statistics\Application\UseCase\Command\CreateLinkClick\CreateLinkClickCommand;
use App\Statistics\Application\UseCase\Command\CreatePageVisit\CreatePageVisitCommand;

readonly class StatisticsInteractor
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function createPageVisit(string $pageId, string $timestamp, string $userAgent): void
    {
        $command = new CreatePageVisitCommand(
            pageId: $pageId,
            timestamp: $timestamp,
            userAgent: $userAgent
        );

        $this->commandBus->dispatch($command);
    }

    public function createLinkClick(string $linkId, string $pageId, string $timestamp, string $userAgent): void
    {
        $command = new CreateLinkClickCommand(
            linkId: $linkId,
            pageId: $pageId,
            timestamp: $timestamp,
            userAgent: $userAgent
        );

        $this->commandBus->dispatch($command);
    }
}
