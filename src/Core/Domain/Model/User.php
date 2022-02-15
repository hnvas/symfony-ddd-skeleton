<?php
declare(strict_types = 1);

namespace App\Core\Domain\Model;

use App\Core\Domain\Enum\UserRoleEnum;
use App\Core\Infrastructure\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
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
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return \App\Core\Domain\Model\User
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return \App\Core\Domain\Model\User
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
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

    /**
     * @param array $roles
     *
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
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
