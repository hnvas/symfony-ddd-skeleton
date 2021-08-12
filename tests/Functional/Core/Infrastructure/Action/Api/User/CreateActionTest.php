<?php
declare(strict_types = 1);

namespace App\Tests\Functional\Core\Infrastructure\Action\Api\User;

use App\Tests\Util\LoginTrait;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateActionTest extends WebTestCase
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

    public function testUserShouldBeCreatedWithValidEmailNameAndPassword()
    {
        $token = $this->login($this->client);

        $this->client->request("POST", "/api/user", [], [], [
            'CONTENT_TYPE'       => 'application/json',
            'HTTP_AUTHORIZATION' => $token
        ], json_encode([
            'email'    => 'newuser@test.com',
            'name'     => 'newuser',
            'password' => 'qwerty'
        ]));

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
    }

    public function testUserShouldNotBeCreatedWithExistentEmail()
    {
        $token = $this->login($this->client);

        $this->client->request("POST", "/api/user", [], [], [
            'CONTENT_TYPE'       => 'application/json',
            'HTTP_AUTHORIZATION' => $token
        ], json_encode([
            'email'    => 'user@admin.com',
            'name'     => 'admin',
            'password' => '12345678910'
        ]));

        $this->assertEquals(422, $this->client->getResponse()->getStatusCode());
    }

}
