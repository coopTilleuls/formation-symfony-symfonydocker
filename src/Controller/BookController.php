<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class BookController extends AbstractController
{
    #[Route('/books/{id<\d+>}', name: 'book_show', methods: ['GET'])]
    public function show(int $id, BookRepository $bookRepository): JsonResponse
    {
        $book = $bookRepository->find($id) ?? throw $this->createNotFoundException();

        return $this->json($book);
    }
}
