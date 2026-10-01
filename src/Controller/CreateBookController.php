<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Service\BookCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CreateBookController extends AbstractController
{
    #[Route('/books/create', name: 'book_create', methods: ['GET', 'POST'])]
    public function index(Request $request, BookCreator $bookCreator): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookCreator->create($book);

            $this->addFlash('success', \sprintf('Le livre « %s » a été créé (id %d).', $book->getTitle(), $book->getId()));

            return $this->redirectToRoute('book_create');
        }

        return $this->render('create_book/index.html.twig', [
            'form' => $form,
        ]);
    }
}
