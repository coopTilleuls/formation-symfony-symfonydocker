<?php

namespace App\Repository;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

class BookRepository
{
    public function __construct(
        #[Autowire('%kernel.project_dir%/data/books.json')]
        private readonly string $booksFile,
    ) {
    }

    public function findAll(): array
    {
        return json_decode(file_get_contents($this->booksFile), true);
    }

    public function find(int $id): ?array
    {
        foreach ($this->findAll() as $book) {
            if ($book['id'] === $id) {
                return $book;
            }
        }

        return null;
    }
}
