<?php

declare(strict_types=1);

namespace App\UserPage\Domain\Entity;

use App\Shared\Domain\Service\IdGenerator;

class Link
{
    private string $id;

    public function __construct(private Page $page, private string $url, private string $title)
    {
        $this->id = IdGenerator::generate();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPage(): Page
    {
        return $this->page;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}
