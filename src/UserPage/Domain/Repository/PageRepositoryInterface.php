<?php

declare(strict_types=1);

namespace App\UserPage\Domain\Repository;

use App\UserPage\Domain\Entity\Page;

interface PageRepositoryInterface
{
    public function save(Page $page): void;

    public function findById(string $id): Page;

    public function findByIdWithLinks(string $pageId): ?Page;

    public function findByUserId(string $userId): ?Page;

    public function findByUsername(string $username): ?Page;

    public function clearLinks(Page $page): void;
}
