<?php
declare(strict_types = 1);

namespace App\Tests\Functional\Core\Infrastructure\Controller\Auth;

use App\Tests\Functional\Util\LoginTrait;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ResourcesActionTest extends WebTestCase
{
    use FixturesTrait, LoginTrait;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();

        $this->loadFixtures([
            'App\Core\Infrastructure\DataFixtures\UserFixtures',
            'App\Core\Infrastructure\DataFixtures\PermissionFixtures'
        ]);
    }

    public function testShouldListResourcesWhenUserIsAuthenticated()
    {
        $token = $this->login($this->client);
        $this->client->request(
            "GET",
            '/auth/resources',
            [],
            [],
            [
                'CONTENT_TYPE'       => 'application/json',
                'HTTP_AUTHORIZATION' => $token
            ]
        );

        $response = $this->client->getResponse();
        $modules = json_decode($response->getContent());
        $permissions = current($modules)->permissions;

        self::assertNotEmpty($modules);
        self::assertNotEmpty($permissions);
    }

}
