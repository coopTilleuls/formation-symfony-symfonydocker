<?php

namespace App\Repository;

use App\Model\Book;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class BookRepository
{
    public function __construct(
        #[Autowire('%kernel.project_dir%/data/books.json')]
        private readonly string $booksFile,
    ) {
    }

    /**
     * @return Book[]
     */
    public function findAll(): array
    {
        $rows = json_decode(file_get_contents($this->booksFile), true);

        return array_map(
            static fn (array $row): Book => new Book($row['id'], $row['title'], $row['author']),
            $rows,
        );
    }

    public function find(int $id): ?Book
    {
        foreach ($this->findAll() as $book) {
            if ($book->id === $id) {
                return $book;
            }
        }

        return null;
    }
}
