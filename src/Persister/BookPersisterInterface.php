<?php

namespace App\Persister;

use App\Entity\Book;

// Variante : #[AutoconfigureTag] pour récupérer toutes les implémentations avec #[AutowireIterator] ou #[AutowireLocator]
interface BookPersisterInterface
{
    public function exists(string $title, string $author): bool;

    public function persist(Book $book): void;
}
