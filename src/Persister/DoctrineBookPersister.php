<?php

namespace App\Persister;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

// Variante : #[AsAlias(BookPersisterInterface::class, when: ['dev', 'prod'])] pour ne l'utiliser qu'en dev et en prod
#[AsAlias(BookPersisterInterface::class)]
final class DoctrineBookPersister implements BookPersisterInterface
{
    public function __construct(
        private readonly BookRepository $bookRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function exists(string $title, string $author): bool
    {
        return null !== $this->bookRepository->findOneBy(['title' => $title, 'author' => $author]);
    }

    public function persist(Book $book): void
    {
        $this->entityManager->persist($book);
        $this->entityManager->flush();
    }
}
