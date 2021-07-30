<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Security;

use DateTimeImmutable;

class TokenPayload
{

    public ?string $username;

    public ?DateTimeImmutable $expiresIn;

    /**
     * TokenPayload constructor.
     *
     * @param string|null $username
     * @param \DateTimeImmutable|null $expiresIn
     */
    public function __construct(
        ?string $username = null,
        ?DateTimeImmutable $expiresIn = null
    ) {
        $this->username  = $username;
        $this->expiresIn = $expiresIn;
    }

    public function toArray(): array
    {
        return [
            "username"  => $this->username,
            "expiresIn" => $this->expiresIn->getTimestamp()
        ];
    }

}
