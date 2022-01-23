<?php
declare(strict_types = 1);

namespace App\Tests\Functional\Core\Infrastructure\Controller\Resource;

use App\Tests\Util\LoginTrait;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseResourceTest extends WebTestCase
{
    use FixturesTrait, LoginTrait;

    private KernelBrowser $client;

    protected const FIXTURES = [];

    public function setUp(): void
    {
        static::ensureKernelShutdown();
        $this->client = static::createClient();

        $this->loadFixtures(static::FIXTURES);
    }

    private function assembleHeaders(string $token): array
    {
        return [
            'CONTENT_TYPE'       => 'application/json',
            'HTTP_AUTHORIZATION' => $token
        ];
    }

    protected function post(string $uri, array $payload): Response
    {
        $token = $this->login($this->client);
        $headers = $this->assembleHeaders($token);
        $content = json_encode($payload);

        $this->client->request("POST", $uri, [], [], $headers, $content);

        return $this->client->getResponse();
    }

    protected function delete(string $uri): Response
    {
        $token = $this->login($this->client);
        $headers = $this->assembleHeaders($token);

        $this->client->request("DELETE", $uri, [], [], $headers);

        return $this->client->getResponse();
    }

    protected function get(string $uri, array $parameters = []): Response
    {
        $token = $this->login($this->client);
        $headers = $this->assembleHeaders($token);

        $this->client->request("GET", $uri, $parameters, [], $headers);

        return $this->client->getResponse();
    }

    protected function put(string $uri, array $payload = []): Response
    {
        $token = $this->login($this->client);
        $headers = $this->assembleHeaders($token);
        $content = json_encode($payload);

        $this->client->request("PUT", $uri, [], [], $headers, $content);

        return $this->client->getResponse();
    }

    /**
     * @dataProvider validCreateData
     */
    public function testResourceShouldBeCreated(string $uri, array $payload)
    {
        $response = $this->post($uri, $payload);

        $this->assertEquals(201, $response->getStatusCode());
    }

    /**
     * @dataProvider invalidCreateData
     */
    public function testResourceShouldNotBeCreatedWithInvalidData(string $uri, array $payload)
    {
        $response = $this->post($uri, $payload);

        $this->assertEquals(422, $response->getStatusCode());
    }

    /**
     * @dataProvider deleteData
     */
    public function testResourceShouldBeDeleted(string $uri, int $id)
    {
        $response = $this->delete($uri . $id);

        $this->assertEquals(204, $response->getStatusCode());
    }

    /**
     * @dataProvider indexData
     */
    public function testResourceIndex(string $uri)
    {
        $response = $this->get($uri);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @dataProvider readData
     */
    public function testResourceShouldBeRead(string $uri, int $id)
    {
        $response = $this->get($uri . $id);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @dataProvider updateData
     */
    public function testResourceShouldBeUpdated(string $uri, int $id, array $payload)
    {
        $response = $this->put($uri . $id, $payload);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public abstract function validCreateData(): array;

    public abstract function invalidCreateData(): array;

    public abstract function deleteData(): array;

    public abstract function indexData(): array;

    public abstract function readData(): array;

    public abstract function updateData(): array;
}
