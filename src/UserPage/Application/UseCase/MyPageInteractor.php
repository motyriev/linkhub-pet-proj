<?php

declare(strict_types=1);

namespace App\UserPage\Application\UseCase;

use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Query\QueryBusInterface;
use App\Statistics\Application\UseCase\Query\GetLinkClicks\GetLinkClicksQuery;
use App\Statistics\Application\UseCase\Query\GetPageVisits\GetPageVisitsQuery;
use App\UserPage\Application\DTO\MyPageWithStatisticsDTO;
use App\UserPage\Application\UseCase\Command\UpdatePage\UpdatePageCommand;
use App\UserPage\Application\UseCase\Query\FindPageByUserId\FindPageByUserIdQuery;

readonly class MyPageInteractor
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
    ) {
    }

    public function getMyPageWithStatistics(string $userId): MyPageWithStatisticsDTO
    {
        $pageDTO = $this->queryBus->execute(new FindPageByUserIdQuery($userId));
        $pageVisitDTOs = $this->queryBus->execute(new GetPageVisitsQuery($pageDTO->pageId));
        $linkClickDTOs = $this->queryBus->execute(new GetLinkClicksQuery($pageDTO->pageId));

        return new MyPageWithStatisticsDTO($pageDTO, $pageVisitDTOs, $linkClickDTOs);
    }

    public function updateMyPage(string $pageId, string $description, array $linksData): void
    {
        $command = new UpdatePageCommand(
            pageId: $pageId,
            description: $description,
            linksData: $linksData
        );

        $this->commandBus->dispatch($command);
    }
}
