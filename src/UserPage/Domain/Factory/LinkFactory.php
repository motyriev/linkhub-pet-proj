<?php

declare(strict_types=1);

namespace App\UserPage\Domain\Factory;

use App\UserPage\Domain\Entity\Link;
use App\UserPage\Domain\Entity\Page;

class LinkFactory
{
    public function create(Page $page, string $url, string $title): Link
    {
        return new Link($page, $url, $title);
    }
}
