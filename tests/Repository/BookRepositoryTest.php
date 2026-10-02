<?php

namespace App\Tests\Repository;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class BookRepositoryTest extends KernelTestCase
{
    public function testFindAuthorBooks(): void
    {
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        (new SchemaTool($entityManager))->createSchema($entityManager->getMetadataFactory()->getAllMetadata());

        $entityManager->persist((new Book())->setTitle('Notre-Dame de Paris')->setAuthor('victor.hugo'));
        $entityManager->persist((new Book())->setTitle('Germinal')->setAuthor('emile.zola'));
        $entityManager->persist((new Book())->setTitle('Les Misérables')->setAuthor('victor.hugo'));
        $entityManager->flush();

        $books = static::getContainer()->get(BookRepository::class)->findAuthorBooks('victor.hugo');

        $this->assertSame(
            ['Les Misérables', 'Notre-Dame de Paris'],
            array_map(fn (Book $book) => $book->getTitle(), $books),
        );
    }
}
