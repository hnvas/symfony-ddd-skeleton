<?php

namespace App\Security;

use App\Security\ValueObject\TokenPayload;

interface TokenServiceInterface
{

    public function tokenType(): string;

    public function decodeToken(string $token): TokenPayload;

    public function encodeToken(TokenPayload $payload): string;

}
