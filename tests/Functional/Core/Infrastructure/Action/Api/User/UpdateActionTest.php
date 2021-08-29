<?php
declare(strict_types = 1);

namespace App\Tests\Functional\Core\Infrastructure\Action\Api\User;

use App\Tests\Util\LoginTrait;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UpdateActionTest extends WebTestCase
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

    public function testUserShouldBeUpdatedWithValidData()
    {
        $token = $this->login($this->client);
        $userId = 3;

        $this->client->request("PUT", "/api/user/{$userId}", [], [], [
            'CONTENT_TYPE'       => 'application/json',
            'HTTP_AUTHORIZATION' => $token
        ], json_encode([
            'email'    => 'newuser@test.com',
            'name'     => 'newuser',
            'password' => 'qwerty'
        ]));

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

}
