<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Security;

interface TokenServiceInterface
{

    public function tokenType(): string;

    public function tokenExpired(TokenPayload $payload): bool;

    public function decodeToken(string $token): TokenPayload;

    public function encodeToken(TokenPayload $payload): string;

}
