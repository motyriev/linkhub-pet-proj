<?php

declare(strict_types=1);

namespace App\Statistics\Application\UseCase\Query\GetLinkClicks;

use App\Shared\Application\Query\AbstractQuery;

readonly class GetLinkClicksQuery extends AbstractQuery
{
    public function __construct(public string $pageId)
    {
    }
}
