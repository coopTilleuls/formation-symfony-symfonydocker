<?php

namespace App\Security\Voter;

use App\Model\Book;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @extends Voter<string, Book>
 */
final class BookVoter extends Voter
{
    public const VIEW = 'BOOK_VIEW';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::VIEW === $attribute && $subject instanceof Book;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        return \in_array('ROLE_ADMIN', $token->getRoleNames(), true)
            || $subject->author === $token->getUserIdentifier();
    }
}
