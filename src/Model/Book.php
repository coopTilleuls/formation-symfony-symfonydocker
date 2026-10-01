<?php

namespace App\Model;

final class Book
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $author,
    ) {
    }
}
