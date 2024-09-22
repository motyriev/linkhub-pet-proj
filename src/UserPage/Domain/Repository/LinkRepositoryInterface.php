<?php

declare(strict_types=1);

namespace App\UserPage\Domain\Repository;

use App\UserPage\Domain\Entity\Link;

interface LinkRepositoryInterface
{
    public function save(Link $link): void;

    public function findById(string $id): ?Link;

    public function findByPageId(string $pageId): array;

    public function remove(Link $link): void;
}
