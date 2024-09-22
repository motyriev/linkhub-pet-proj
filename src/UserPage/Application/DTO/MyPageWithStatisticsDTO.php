<?php

declare(strict_types=1);

namespace App\UserPage\Application\DTO;

use App\Statistics\Application\DTO\LinkClick\LinkClickDTO;
use App\Statistics\Application\DTO\PageVisit\PageVisitDTO;

readonly class MyPageWithStatisticsDTO
{
    /**
     * @param PageVisitDTO[] $pageVisits
     * @param LinkClickDTO[] $linkClicks
     */
    public function __construct(
        private PageDTO $pageDTO,
        private array $pageVisits,
        private array $linkClicks,
    ) {
    }

    public function getPageDTO(): PageDTO
    {
        return $this->pageDTO;
    }

    /**
     * @return PageVisitDTO[]
     */
    public function getPageVisitsDTOs(): array
    {
        return $this->pageVisits;
    }

    /**
     * @return LinkClickDTO[]
     */
    public function getLinkClicksDTOs(): array
    {
        return $this->linkClicks;
    }
}
