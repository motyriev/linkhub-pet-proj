<?php

declare(strict_types=1);

namespace App\Statistics\Application\UseCase\Query\GetPageVisits;

use App\Shared\Application\Query\AbstractQueryHandler;
use App\Statistics\Application\DTO\PageVisit\PageVisitDTO;
use App\Statistics\Application\DTO\PageVisit\PageVisitDTOTransformer;
use App\Statistics\Domain\Repository\PageVisitRepositoryInterface;

readonly class GetPageVisitsQueryHandler extends AbstractQueryHandler
{
    public function __construct(
        private PageVisitRepositoryInterface $pageVisitRepo,
        private PageVisitDTOTransformer $transformer,
    ) {
    }

    /**
     * @return PageVisitDTO[]
     */
    public function __invoke(GetPageVisitsQuery $q): array
    {
        $pageVisits = $this->pageVisitRepo->getByCriteria(['page_id' => $q->pageId]);

        return $this->transformer->fromEntities($pageVisits);
    }
}
