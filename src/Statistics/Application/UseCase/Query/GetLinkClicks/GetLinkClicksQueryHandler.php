<?php

declare(strict_types=1);

namespace App\Statistics\Application\UseCase\Query\GetLinkClicks;

use App\Shared\Application\Query\AbstractQueryHandler;
use App\Statistics\Application\DTO\LinkClick\LinkClickDTOTransformer;
use App\Statistics\Application\DTO\PageVisit\PageVisitDTO;
use App\Statistics\Domain\Repository\LinkClickRepositoryInterface;

readonly class GetLinkClicksQueryHandler extends AbstractQueryHandler
{
    public function __construct(
        private LinkClickRepositoryInterface $linkClickRepository,
        private LinkClickDTOTransformer $transformer,
    ) {
    }

    /**
     * @return PageVisitDTO[]
     */
    public function __invoke(GetLinkClicksQuery $q): array
    {
        $pageVisits = $this->linkClickRepository->getByCriteria(['page_id' => $q->pageId]);

        return $this->transformer->fromEntities($pageVisits);
    }
}
