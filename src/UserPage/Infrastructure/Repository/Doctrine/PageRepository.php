<?php

declare(strict_types=1);

namespace App\UserPage\Infrastructure\Repository\Doctrine;

use App\UserPage\Domain\Entity\Page;
use App\UserPage\Domain\Repository\PageRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class PageRepository extends ServiceEntityRepository implements PageRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Page::class);
        $this->entityManager = $this->getEntityManager();
    }

    public function save(Page $page): void
    {
        $this->entityManager->persist($page);
        $this->entityManager->flush();
    }

    public function findById(string $id): Page
    {
        return $this->find($id);
    }

    public function findByUserId(string $userId): ?Page
    {
        return $this->findOneBy(['user' => $userId]);
    }

    public function clearLinks(Page $page): void
    {
        foreach ($page->getLinks() as $link) {
            $page->removeLink($link);
            $this->entityManager->remove($link);
        }
    }

    public function findByIdWithLinks(string $pageId): ?Page
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.links', 'l')
            ->addSelect('l')
            ->where('p.id = :pageId')
            ->setParameter('pageId', $pageId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByUsername(string $username): ?Page
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.user', 'u')
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
