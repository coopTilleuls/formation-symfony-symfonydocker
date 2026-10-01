<?php

namespace App\Repository;

use App\Entity\Book;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class JsonBookRepository
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
            static fn (array $row): Book => (new Book())
                ->setId($row['id'])
                ->setTitle($row['title'])
                ->setAuthor($row['author']),
            $rows,
        );
    }

    public function find(int $id): ?Book
    {
        foreach ($this->findAll() as $book) {
            if ($book->getId() === $id) {
                return $book;
            }
        }

        return null;
    }
}
