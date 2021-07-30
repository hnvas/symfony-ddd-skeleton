<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Security;

use DateTimeImmutable;
use Firebase\JWT\JWT;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class JwtTokenService implements TokenServiceInterface
{

    private const ALGORITHM  = 'HS256';
    private const TOKEN_TYPE = 'Bearer';

    /**
     * @var string
     */
    protected string $jwtSecret;

    /**
     * JwtService constructor.
     *
     * @param \Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface $params
     */
    public function __construct(ContainerBagInterface $params)
    {
        $this->jwtSecret = $params->get('jwt_secret');
    }

    public function tokenType(): string
    {
        return self::TOKEN_TYPE;
    }

    public function decodeToken(string $token): TokenPayload
    {
        $accessToken = ltrim(str_replace(self::TOKEN_TYPE, '', $token));
        $credentials = JWT::decode($accessToken, $this->jwtSecret, [self::ALGORITHM]);

        return new TokenPayload(
            $credentials->username,
            (new DateTimeImmutable())->setTimestamp($credentials->expiresIn)
        );
    }

    public function encodeToken(TokenPayload $payload): string
    {
        return JWT::encode($payload->toArray(), $this->jwtSecret, self::ALGORITHM);
    }

    public function tokenExpired(TokenPayload $payload): bool
    {
        return $payload->expiresIn < new DateTimeImmutable();
    }

}
