<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Security;

use App\Core\Domain\Model\User;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function model(): User
    {
        return $this->user;
    }

    public function getRoles(): array
    {
        return $this->user->roles();
    }

    public function getPassword(): ?string
    {
        return $this->user->password();
    }

    /**
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
    }

    public function getUsername(): ?string
    {
        return $this->user->email();
    }

    public function getUserIdentifier(): string
    {
        return $this->user->email();
    }
}
