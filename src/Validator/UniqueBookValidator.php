<?php

namespace App\Validator;

use App\Entity\Book;
use App\Service\BookCreator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class UniqueBookValidator extends ConstraintValidator
{
    public function __construct(
        private readonly BookCreator $bookCreator,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueBook) {
            throw new UnexpectedTypeException($constraint, UniqueBook::class);
        }

        if (!$value instanceof Book) {
            throw new UnexpectedValueException($value, Book::class);
        }

        if (null === $value->getTitle() || null === $value->getAuthor()) {
            return;
        }

        if ($this->bookCreator->exists($value->getTitle(), $value->getAuthor())) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ title }}', $value->getTitle())
                ->setParameter('{{ author }}', $value->getAuthor())
                ->atPath('title')
                ->addViolation();
        }
    }
}
