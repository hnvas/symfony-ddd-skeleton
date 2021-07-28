<?php

namespace App\Core\Infrastructure\Security;

class TokenPayload
{

    public ?string $username;

    public ?\DateTimeImmutable $expiresIn;

    /**
     * TokenPayload constructor.
     *
     * @param string|null $username
     * @param \DateTimeImmutable|null $expiresIn
     */
    public function __construct(
        ?string $username = null,
        ?\DateTimeImmutable $expiresIn = null
    ) {
        $this->username  = $username;
        $this->expiresIn = $expiresIn;
    }

    public function toArray()
    {
        return [
            "username"  => $this->username,
            "expiresIn" => $this->expiresIn->getTimestamp()
        ];
    }

}
