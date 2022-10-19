<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Security;

use DateTimeImmutable;

/**
 * Class TokenPayload
 * @package App\Core\Infrastructure\Security
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class TokenPayload
{

    public ?string $username;

    public ?bool $active;

    public ?bool $verified;

    public ?DateTimeImmutable $expiresIn;

    /**
     * TokenPayload constructor.
     *
     * @param string|null $username
     * @param bool|null $active
     * @param bool|null $verified
     * @param \DateTimeImmutable|null $expiresIn
     */
    public function __construct(
        ?string            $username = null,
        ?bool              $active = null,
        ?bool              $verified = null,
        ?DateTimeImmutable $expiresIn = null
    ) {
        $this->username  = $username;
        $this->expiresIn = $expiresIn;
        $this->active    = $active;
        $this->verified  = $verified;
    }

    public function toArray(): array
    {
        return [
            "username"  => $this->username,
            "active"    => $this->active,
            "verified"  => $this->verified,
            "expiresIn" => $this->expiresIn->getTimestamp()
        ];
    }

}
