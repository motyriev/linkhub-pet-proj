<?php

declare(strict_types=1);

namespace App\UserPage\Domain\Entity;

use App\Shared\Domain\Service\IdGenerator;
use App\User\Domain\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Page
{
    private string $id;
    private Collection $links;
    private User $user;

    public function __construct(
        private string $description,
    ) {
        $this->id = IdGenerator::generate();
        $this->links = new ArrayCollection();
    }

    public function isOwnedBy(string $userId): bool
    {
        return $this->user->getId() === $userId;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $d): void
    {
        $this->description = $d;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getLinks(): Collection
    {
        return $this->links;
    }

    public function addLink(Link $link): void
    {
        if (!$this->links->contains($link)) {
            $this->links->add($link);
        }
    }

    public function removeLink(Link $link): void
    {
        if ($this->links->contains($link)) {
            $this->links->removeElement($link);
        }
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}
