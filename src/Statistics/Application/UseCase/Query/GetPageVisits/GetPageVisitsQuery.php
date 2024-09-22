<?php

declare(strict_types=1);

namespace App\Statistics\Application\UseCase\Query\GetPageVisits;

use App\Shared\Application\Query\AbstractQuery;

readonly class GetPageVisitsQuery extends AbstractQuery
{
    public function __construct(public string $pageId)
    {
    }
}
