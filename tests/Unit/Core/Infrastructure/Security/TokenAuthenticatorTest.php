<?php
declare(strict_types = 1);

namespace App\Tests\Unit\Core\Infrastructure\Security;

use App\Core\Infrastructure\Security\JwtTokenService;
use App\Core\Infrastructure\Security\TokenAuthenticator;
use App\Core\Infrastructure\Security\TokenPayload;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;

class TokenAuthenticatorTest extends TestCase
{

    private JwtTokenService    $tokenService;
    private TokenAuthenticator $instance;

    public function setUp(): void
    {
        $containerBagMock = self::createMock(ContainerBagInterface::class);
        $containerBagMock
            ->method('get')
            ->with('jwt_secret')
            ->willReturn('anything');

        $this->tokenService = new JwtTokenService($containerBagMock);
        $this->instance     = new TokenAuthenticator($this->tokenService);
    }

    /**
     * @dataProvider provideHeaders
     */
    public function testShouldValidateIfAuthorizationIsPresent(
        array $headers,
        bool $expected
    ): void {
        $requestMock          = self::createMock(Request::class);
        $requestMock->headers = new HeaderBag($headers);

        $result = $this->instance->supports($requestMock);

        self::assertEquals($expected, $result);
    }

    public function provideHeaders(): array
    {
        return [
            'valid' => [
                'value' => ['Authorization' => 'token'],
                'expected' => true
            ],
            'invalid' => [
                'value' => ['Content-Type'  => 'application/json'],
                'expected' => false
            ]
        ];
    }

    public function testShouldAuthenticateWithToken(): void
    {
        $expires = new DateTimeImmutable('+12 hours');
        $payload = new TokenPayload('admin', true, true, $expires);
        $token   = $this->tokenService->encodeToken($payload);

        $headers = self::createMock(HeaderBag::class);
        $headers->method('get')
                ->with('Authorization')
                ->willReturn($token);

        $requestMock          = self::createMock(Request::class);
        $requestMock->headers = $headers;

        $result = $this->instance->authenticate($requestMock);

        self::assertInstanceOf(PassportInterface::class, $result);
    }

    public function testShouldThrowExceptionWhenTokenIsExpired(): void
    {
        $expires = new DateTimeImmutable('-12 hours');
        $payload = new TokenPayload('admin', true, true, $expires);
        $token   = $this->tokenService->encodeToken($payload);

        $headers = self::createMock(HeaderBag::class);
        $headers->method('get')
                ->with('Authorization')
                ->willReturn($token);

        $requestMock          = self::createMock(Request::class);
        $requestMock->headers = $headers;

        self::expectException(AuthenticationException::class);
        self::expectExceptionMessage("Token has expired");

        $this->instance->authenticate($requestMock);
    }
}
