<?php

namespace App\Service;

use App\Entity\Book;
use App\Persister\BookPersisterInterface;

final class BookCreator
{
    public function __construct(
        // Variante : #[Autowire(service: JsonBookPersister::class)] pour forcer le JSON dans ce service uniquement
        // Variante : #[AutowireIterator(BookPersisterInterface::class)] iterable $bookPersisters pour écrire dans tous les stockages (nécessite #[AutoconfigureTag] sur l'interface)
        private readonly BookPersisterInterface $bookPersister,
    ) {
    }

    public function exists(string $title, string $author): bool
    {
        return $this->bookPersister->exists($title, $author);
    }

    public function create(Book $book): Book
    {
        $this->bookPersister->persist($book);

        return $book;
    }
}
