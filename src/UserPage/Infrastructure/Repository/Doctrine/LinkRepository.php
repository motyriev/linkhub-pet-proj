<?php

declare(strict_types=1);

namespace App\UserPage\Infrastructure\Repository\Doctrine;

use App\UserPage\Domain\Entity\Link;
use App\UserPage\Domain\Repository\LinkRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class LinkRepository extends ServiceEntityRepository implements LinkRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Link::class);
        $this->entityManager = $this->getEntityManager();
    }

    public function save(Link $link): void
    {
        $this->entityManager->persist($link);
        $this->entityManager->flush();
    }

    public function persist(Link $link): void
    {
        $this->entityManager->persist($link);
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function findById(string $id): ?Link
    {
        return $this->find($id);
    }

    /**
     * @return Link[]
     */
    public function findByPageId(string $pageId): array
    {
        return $this->findBy(['page' => $pageId]);
    }

    public function remove(Link $link): void
    {
        $this->entityManager->remove($link);
        $this->entityManager->flush();
    }
}
