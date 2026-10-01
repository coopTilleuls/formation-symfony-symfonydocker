<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @return Book[]
     */
    public function findAuthorBooks(string $author): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.author = :author')
            ->setParameter('author', $author)
            ->orderBy('b.title', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
