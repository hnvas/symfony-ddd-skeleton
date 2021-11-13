<?php
declare(strict_types = 1);

namespace App\Tests\Functional\Core\Infrastructure\Action\Api\User;

use App\Tests\Util\LoginTrait;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexActionTest extends WebTestCase
{

    use FixturesTrait, LoginTrait;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();

        $this->loadFixtures([
            'App\Core\Infrastructure\DataFixtures\UserFixtures'
        ]);
    }

    public function testUserShouldViewAListOfRegisteredUsers()
    {
        $token = $this->login($this->client);

        $this->client->request("GET", "/api/user", [], [], [
            'CONTENT_TYPE'       => 'application/json',
            'HTTP_AUTHORIZATION' => $token
        ]);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}