<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class AuthorController extends AbstractController
{
    #[Route('/authors/{author}/books', name: 'author_books', methods: ['GET'])]
    public function books(string $author, BookRepository $bookRepository): JsonResponse
    {
        return $this->json($bookRepository->findAuthorBooks($author));
    }
}
