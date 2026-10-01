<?php

namespace App\Persister;

use App\Entity\Book;
use App\Repository\JsonBookRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

// Variante : #[AsAlias(BookPersisterInterface::class)] (à retirer de DoctrineBookPersister) pour en faire l'implémentation par défaut
// Variante : #[AsAlias(BookPersisterInterface::class, when: 'test')] pour ne l'utiliser qu'en test
final class JsonBookPersister implements BookPersisterInterface
{
    public function __construct(
        private readonly JsonBookRepository $jsonBookRepository,
        #[Autowire('%kernel.project_dir%/data/books.json')]
        private readonly string $booksFile,
    ) {
    }

    public function exists(string $title, string $author): bool
    {
        foreach ($this->jsonBookRepository->findAll() as $book) {
            if ($book->getTitle() === $title && $book->getAuthor() === $author) {
                return true;
            }
        }

        return false;
    }

    public function persist(Book $book): void
    {
        $books = json_decode(file_get_contents($this->booksFile), true);

        $ids = array_column($books, 'id');
        $book->setId([] === $ids ? 1 : max($ids) + 1);

        $books[] = ['id' => $book->getId(), 'title' => $book->getTitle(), 'author' => $book->getAuthor()];

        $json = json_encode($books, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_UNICODE | \JSON_UNESCAPED_SLASHES | \JSON_THROW_ON_ERROR);
        file_put_contents($this->booksFile, $json."\n", \LOCK_EX);
    }
}
