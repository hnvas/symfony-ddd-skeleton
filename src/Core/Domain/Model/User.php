<?php
declare(strict_types = 1);

namespace App\Core\Domain\Model;

use App\Core\Domain\Enum\UserRoleEnum;

/**
 * Class User
 * @package App\Core\Domain\Model
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class User implements Entity
{
    private ?int   $id;
    private string $email;
    private string $name;
    private ?array $roles         = [];
    private string $password;
    private bool   $emailVerified = false;
    private bool   $active        = false;

    public function __construct(
        string $email,
        string $name,
        string $password,
        array  $roles = [UserRoleEnum::ROLE_USER],
        ?bool  $emailVerified = false,
        ?bool  $active = false
    ) {
        $this->email         = $email;
        $this->name          = $name;
        $this->roles         = $roles;
        $this->password      = $password;
        $this->emailVerified = $emailVerified;
        $this->active        = $active;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function email(): ?string
    {
        return $this->email;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function roles(): array
    {
        $roles   = $this->roles;
        $roles[] = UserRoleEnum::ROLE_USER;

        return array_unique($roles);
    }

    public function password(): ?string
    {
        return $this->password;
    }

    public function changePassword(string $password): void
    {
        $this->password = $password;
    }

    public function isEmailVerified(): ?bool
    {
        return $this->emailVerified;
    }

    public function verifyEmail(): void
    {
        $this->emailVerified = true;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function activate(): void
    {
        $this->active = true;
    }
}
