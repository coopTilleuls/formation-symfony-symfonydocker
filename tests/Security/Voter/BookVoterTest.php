<?php

namespace App\Tests\Security\Voter;

use App\Entity\Book;
use App\Security\Voter\BookVoter;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\NullToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

final class BookVoterTest extends TestCase
{
    #[Test]
    public function itAbstainsForAnotherAttribute(): void
    {
        $voter = new BookVoter();

        $result = $voter->vote($this->createToken('victor.hugo'), $this->createBook('victor.hugo'), ['BOOK_EDIT']);

        self::assertSame(VoterInterface::ACCESS_ABSTAIN, $result);
    }

    #[Test]
    public function itAbstainsWhenTheSubjectIsNotABook(): void
    {
        $voter = new BookVoter();

        $result = $voter->vote($this->createToken('victor.hugo'), new \stdClass(), [BookVoter::VIEW]);

        self::assertSame(VoterInterface::ACCESS_ABSTAIN, $result);
    }

    #[Test]
    public function itDeniesAnonymousUsers(): void
    {
        $voter = new BookVoter();

        $result = $voter->vote(new NullToken(), $this->createBook('victor.hugo'), [BookVoter::VIEW]);

        self::assertSame(VoterInterface::ACCESS_DENIED, $result);
    }

    #[Test]
    public function itGrantsTheAuthor(): void
    {
        $voter = new BookVoter();

        $result = $voter->vote($this->createToken('victor.hugo'), $this->createBook('victor.hugo'), [BookVoter::VIEW]);

        self::assertSame(VoterInterface::ACCESS_GRANTED, $result);
    }

    #[Test]
    public function itDeniesAnotherUser(): void
    {
        $voter = new BookVoter();

        $result = $voter->vote($this->createToken('emile.zola'), $this->createBook('victor.hugo'), [BookVoter::VIEW]);

        self::assertSame(VoterInterface::ACCESS_DENIED, $result);
    }

    #[Test]
    public function itGrantsAnAdministrator(): void
    {
        $token = $this->createMock(TokenInterface::class);
        $token->expects(self::once())
            ->method('getRoleNames')
            ->willReturn(['ROLE_ADMIN']);
        $token->method('getUserIdentifier')->willReturn('admin');
        $voter = new BookVoter();

        $result = $voter->vote($token, $this->createBook('victor.hugo'), [BookVoter::VIEW]);

        self::assertSame(VoterInterface::ACCESS_GRANTED, $result);
    }

    /**
     * @param string[] $roles
     */
    private function createToken(string $identifier, array $roles = ['ROLE_USER']): TokenInterface
    {
        $token = $this->createMock(TokenInterface::class);
        $token->method('getUserIdentifier')->willReturn($identifier);
        $token->method('getRoleNames')->willReturn($roles);

        return $token;
    }

    private function createBook(string $author): Book
    {
        return (new Book())->setTitle('Les Misérables')->setAuthor($author);
    }
}
