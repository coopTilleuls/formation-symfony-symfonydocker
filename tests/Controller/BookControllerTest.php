<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class BookControllerTest extends WebTestCase
{
    public function testAuthorCanSeeHisBook(): void
    {
        $client = static::createClient();

        $user = static::getContainer()->get(UserProviderInterface::class)->loadUserByIdentifier('emile.zola');
        $client->loginUser($user);

        $client->request('GET', '/books/3');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Germinal', $client->getResponse()->getContent());
    }
}
