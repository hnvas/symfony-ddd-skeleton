<?php

namespace App\Security;

use App\Security\TokenPayload;

interface TokenServiceInterface
{

    public function tokenType(): string;

    public function tokenExpired(TokenPayload $payload): bool;

    public function decodeToken(string $token): TokenPayload;

    public function encodeToken(TokenPayload $payload): string;

}
