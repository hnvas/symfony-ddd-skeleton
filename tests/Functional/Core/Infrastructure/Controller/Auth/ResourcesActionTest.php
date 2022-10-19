<?php
declare(strict_types = 1);

namespace App\Tests\Functional\Core\Infrastructure\Controller\Auth;

use App\Core\Infrastructure\DataFixtures\PermissionFixtures;
use App\Core\Infrastructure\DataFixtures\UserFixtures;
use App\Tests\Functional\Util\FixtureLoaderTrait;
use App\Tests\Functional\Util\LoginTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class ResourcesActionTest
 * @package App\Tests\Functional\Core\Infrastructure\Controller\Auth
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class ResourcesActionTest extends WebTestCase
{
    use LoginTrait, FixtureLoaderTrait;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();

        $this->loadFixtures([UserFixtures::class, PermissionFixtures::class]);
        $this->executeFixtures();
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
        $permissions = json_decode($response->getContent());

        self::assertNotEmpty($permissions);
    }

}
