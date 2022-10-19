<?php
declare(strict_types = 1);

namespace App\Tests\Unit\Core\Infrastructure\Security;

use App\Core\Infrastructure\Security\JwtTokenService;
use App\Core\Infrastructure\Security\TokenPayload;
use App\Core\Infrastructure\Security\TokenServiceInterface;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

/**
 * Class JwtTokenServiceTest
 * @package App\Tests\Unit\Core\Infrastructure\Security
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class JwtTokenServiceTest extends TestCase
{

    private TokenServiceInterface $tokenService;

    public function setUp(): void
    {
        $containerBagMock = self::createMock(ContainerBagInterface::class);
        $containerBagMock
            ->method('get')
            ->with('jwt_secret')
            ->willReturn('anything');

        $this->tokenService = new JwtTokenService($containerBagMock);
    }

    /**
     * @dataProvider provideValidTokenAndPayload
     */
    public function testShouldDecodeToken(
        TokenPayload $expected,
        string       $token
    ): void {
        $result = $this->tokenService->decodeToken($token);

        self::assertInstanceOf(get_class($expected), $result);
        self::assertEquals($expected->username, $result->username);
        self::assertEquals($expected->active, $result->active);
        self::assertEquals($expected->verified, $result->verified);
        self::assertEquals($expected->expiresIn, $result->expiresIn);
    }

    /**
     * @dataProvider provideValidTokenAndPayload
     */
    public function testShouldEncodeToken(
        TokenPayload $payload,
        string       $expected
    ): void {
        $result = $this->tokenService->encodeToken($payload);

        self::assertEquals($expected, $result);
    }

    /**
     * @dataProvider providePayloads
     */
    public function testShouldValidateIfTokenIsExpired(
        TokenPayload $payload,
        bool         $expected
    ): void {
        $result = $this->tokenService->tokenExpired($payload);

        self::assertEquals($expected, $result);
    }

    public function provideValidTokenAndPayload(): array
    {
        $payload = new TokenPayload(
            'admin',
            true,
            true,
            (new DateTimeImmutable())->setTimestamp(1649971906)
        );

        $token = sprintf("%s.%s.%s",
            'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9',
            'eyJ1c2VybmFtZSI6ImFkbWluIiwiYWN0aXZlIjp0cnVlLCJ2ZXJpZmllZCI6dHJ1ZSwiZXhwaXJlc0luIjoxNjQ5OTcxOTA2fQ',
            'DPPYx9cbTlxwWY-qaUJuu0wSQ5vIHGqdRLDpl_4_Kf8'
        );

        return [
            [
                'payload' => $payload,
                'token'   => $token
            ]
        ];
    }

    public function providePayloads(): array
    {
        $futureTimestamp  = new \DateTimeImmutable('+12 hours');
        $expiredTimestamp = new \DateTimeImmutable('-12 hours');

        return [
            'future'  => [
                'value'    => new TokenPayload('user', true, true, $futureTimestamp),
                'expected' => false
            ],
            'expired' => [
                'value'    => new TokenPayload('admin', true, true, $expiredTimestamp),
                'expected' => true
            ]
        ];
    }

}
