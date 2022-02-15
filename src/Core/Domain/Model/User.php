<?php
declare(strict_types = 1);

namespace App\Core\Domain\Model;

use App\Core\Domain\Enum\UserRoleEnum;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Serializer\XmlRoot("user")
 *
 * @Hateoas\Relation("self", href = "expr('/api/user/' ~ object.getId())")
 */
class User extends Entity implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @Serializer\XmlAttribute
     *
     * @var int|null
     */
    private ?int $id;

    /**
     * @Assert\Email
     * @Assert\NotBlank
     *
     * @var string|null
     */
    private ?string $email;

    /**
     * @Assert\NotBlank
     *
     * @var string|null
     */
    private ?string $name;

    /**
     *
     * @Serializer\Accessor(getter="getRoles",setter="setRoles")
     * @Serializer\Type("array<string>")
     * @var array|null
     */
    private ?array $roles = [];

    /**
     * @var string|null The hashed password
     *
     * @Serializer\Exclude(if="context.getDirection() === 1")
     *
     * @var string|null
     */
    private ?string $password;

    /**
     * @var bool|null
     */
    private ?bool $emailVerified = false;

    /**
     * @var bool|null
     */
    private ?bool $active = false;


    public function getId(): ?int
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

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles   = $this->roles;
        $roles[] = UserRoleEnum::ROLE_USER;

        return array_unique($roles);
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
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
}
