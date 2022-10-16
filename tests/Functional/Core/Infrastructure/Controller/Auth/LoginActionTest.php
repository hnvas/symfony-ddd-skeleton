<?php
declare(strict_types = 1);

namespace App\Tests\Functional\Core\Infrastructure\Controller\Auth;

use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginActionTest extends WebTestCase
{

    private KernelBrowser $client;
    private AbstractDatabaseTool $databaseTool;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();
        $this->databaseTool = $this->client->getContainer()
                                     ->get(DatabaseToolCollection::class)
                                     ->get();
    }

    public function testUserShouldLoginWithValidCredentials()
    {
        $this->databaseTool->loadFixtures([
            'App\Core\Infrastructure\DataFixtures\UserFixtures'
        ]);

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
        $this->databaseTool->loadFixtures([
            'App\Core\Infrastructure\DataFixtures\UserFixtures'
        ]);

        $this->client->request("POST", "/auth/login", [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'username' => 'some-user',
            'password' => '123'
        ]));

        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
    }

}
