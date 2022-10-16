<?php
declare(strict_types = 1);

namespace App\Tests\Functional\Core\Infrastructure\Controller\Api;

use App\Tests\Functional\Util\LoginTrait;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseResourceTest extends WebTestCase
{
    use LoginTrait;

    private KernelBrowser $client;
    private AbstractDatabaseTool $databaseTool;

    protected const FIXTURES = [];

    public function setUp(): void
    {
        static::ensureKernelShutdown();
        $this->client = static::createClient();
        $this->databaseTool = $this->client->getContainer()
                                           ->get(DatabaseToolCollection::class)
                                           ->get();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
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
        $this->databaseTool->loadFixtures(static::FIXTURES);

        $response = $this->post($uri, $payload);

        $this->assertEquals(201, $response->getStatusCode());
    }

    /**
     * @dataProvider invalidCreateData
     */
    public function testResourceShouldNotBeCreatedWithInvalidData(string $uri, array $payload)
    {
        $this->databaseTool->loadFixtures(static::FIXTURES);

        $response = $this->post($uri, $payload);

        $this->assertEquals(422, $response->getStatusCode());
    }

    /**
     * @dataProvider deleteData
     */
    public function testResourceShouldBeDeleted(string $uri, int $id)
    {
        $this->databaseTool->loadFixtures(static::FIXTURES);

        $response = $this->delete($uri . $id);

        $this->assertEquals(204, $response->getStatusCode());
    }

    /**
     * @dataProvider indexData
     */
    public function testResourceIndex(string $uri)
    {
        $this->databaseTool->loadFixtures(static::FIXTURES);

        $response = $this->get($uri);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @dataProvider readData
     */
    public function testResourceShouldBeRead(string $uri, int $id)
    {
        $this->databaseTool->loadFixtures(static::FIXTURES);

        $response = $this->get($uri . $id);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @dataProvider updateData
     */
    public function testResourceShouldBeUpdated(string $uri, int $id, array $payload)
    {
        $this->databaseTool->loadFixtures(static::FIXTURES);

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
