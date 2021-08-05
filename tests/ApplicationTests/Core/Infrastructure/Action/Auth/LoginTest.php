<?php
declare(strict_types = 1);

namespace App\Tests\ApplicationTests\Core\Infrastructure\Action\Auth;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{

    private KernelBrowser $client;

    public static function setUpBeforeClass(): void
    {

    }

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();
    }

    public function testUserShouldLoginWithValidCredentials()
    {
        $this->client->request("POST", "/auth/login", [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'username' => 'username',
            'password' => '123456'
        ]));

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
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
