<?php

namespace App\Tests\Security\Voter;

use App\Entity\Book;
use App\Security\Voter\BookVoter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

final class BookVoterDataProviderTest extends TestCase
{
    /**
     * @param string[] $roles
     */
    #[DataProvider('getUsers')]
    public function testOnlyTheAuthorOrAdminCanAccessABook(string $user, array $roles, int $expected): void
    {
        $book = (new Book())->setAuthor('victor.hugo');

        $tokenMock = $this->createMock(TokenInterface::class);
        $tokenMock->method('getUserIdentifier')->willReturn($user);
        $tokenMock->method('getRoleNames')->willReturn($roles);

        $voter = new BookVoter();

        self::assertSame($expected, $voter->vote($tokenMock, $book, [BookVoter::VIEW]));
    }

    /**
     * @return array<array{string, string[], int}>
     */
    public static function getUsers(): array
    {
        return [
            ['victor.hugo', ['ROLE_USER'], VoterInterface::ACCESS_GRANTED],
            ['admin', ['ROLE_ADMIN'], VoterInterface::ACCESS_GRANTED],
            ['emile.zola', ['ROLE_USER'], VoterInterface::ACCESS_DENIED],
        ];
    }
}
