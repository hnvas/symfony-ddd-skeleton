<?php
declare(strict_types = 1);

namespace App\Tests\Functional\Core\Infrastructure\Controller\Auth;

use App\Core\Infrastructure\DataFixtures\UserFixtures;
use App\Tests\Functional\Util\FixtureLoaderTrait;
use App\Tests\Functional\Util\LoginTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ResendVerificationActionTest extends WebTestCase
{

    use LoginTrait, FixtureLoaderTrait;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();

        $this->loadFixtures([UserFixtures::class]);
        $this->executeFixtures();
    }

    public function testItShouldResendUserVerificationEmail()
    {
        $token = $this->login($this->client);
        $this->client->setServerParameter('CONTENT_TYPE', 'application/json');
        $this->client->setServerParameter('HTTP_AUTHORIZATION', $token);
        $this->client->request("GET", '/auth/resend');

        self::assertResponseIsSuccessful();
    }

    public function testItShouldNotSendWhenUserIsNotAuthenticated()
    {
        $this->client->request("GET", '/auth/resend');

        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

}
