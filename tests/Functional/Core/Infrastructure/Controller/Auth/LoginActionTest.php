<?php
declare(strict_types = 1);

namespace App\Tests\Functional\Core\Infrastructure\Controller\Auth;

use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginActionTest extends WebTestCase
{
    use FixturesTrait;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();

        $this->loadFixtures([
            'App\Core\Infrastructure\DataFixtures\UserFixtures'
        ]);
    }

    public function testUserShouldLoginWithValidCredentials()
    {
        $this->client->request("POST", "/auth/login", [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'username' => 'user@admin.com',
            'password' => '12345678910'
        ]));

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertObjectHasAttribute(
            'access_token',
            json_decode($this->client->getResponse()->getContent())
        );
    }

    public function testUserShouldNotLoginWithInvalidCredentials()
    {
        $this->client->request("POST", "/auth/login", [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'username' => 'some-user',
            'password' => '123'
        ]));

        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
    }

}
