<?php

declare(strict_types=1);

namespace App\UserPage\Domain\Factory;

use App\User\Domain\Entity\User;
use App\UserPage\Domain\Entity\Page;

class PageFactory
{
    public function __construct(private readonly LinkFactory $linkFactory)
    {
    }

    public function create(User $user, string $description, array $linksData = []): Page
    {
        $page = new Page($description);

        $page->setUser($user);

        foreach ($linksData as $data) {
            $link = $this->linkFactory->create($page, $data['url'], $data['text']);
            $page->addLink($link);
        }

        return $page;
    }
}
