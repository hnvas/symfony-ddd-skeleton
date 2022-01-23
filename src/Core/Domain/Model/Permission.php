<?php
declare(strict_types = 1);

namespace App\Core\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;
use App\Core\Infrastructure\Repository\PermissionRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PermissionRepository::class)
 * @ORM\Table(name="`permission`")
 *
 * @Serializer\XmlRoot("permission")
 *
 * @Hateoas\Relation("self", href = "expr('/api/permission/' ~ object.getId())")
 */
class Permission extends Entity
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Serializer\XmlAttribute
     *
     * @var int|null
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank
     *
     * @var string|null
     */
    private ?string $role;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     *
     * @var string|null
     */
    private ?string $resource;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank
     *
     * @var bool|null
     */
    private ?bool $canCreate;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank
     *
     * @var bool|null
     */
    private ?bool $canRead;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank
     *
     * @var bool|null
     */
    private ?bool $canUpdate;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank
     *
     * @var bool|null
     */
    private ?bool $canDelete;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank
     *
     * @var bool|null
     */
    private ?bool $canIndex;

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
    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * @param string|null $role
     */
    public function setRole(?string $role): void
    {
        $this->role = $role;
    }

    /**
     * @return string|null
     */
    public function getResource(): ?string
    {
        return $this->resource;
    }

    /**
     * @param string|null $resource
     */
    public function setResource(?string $resource): void
    {
        $this->resource = $resource;
    }

    /**
     * @return bool|null
     */
    public function getCanCreate(): ?bool
    {
        return $this->canCreate;
    }

    /**
     * @param bool|null $canCreate
     */
    public function setCanCreate(?bool $canCreate): void
    {
        $this->canCreate = $canCreate;
    }

    /**
     * @return bool|null
     */
    public function getCanRead(): ?bool
    {
        return $this->canRead;
    }

    /**
     * @param bool|null $canRead
     */
    public function setCanRead(?bool $canRead): void
    {
        $this->canRead = $canRead;
    }

    /**
     * @return bool|null
     */
    public function getCanUpdate(): ?bool
    {
        return $this->canUpdate;
    }

    /**
     * @param bool|null $canUpdate
     */
    public function setCanUpdate(?bool $canUpdate): void
    {
        $this->canUpdate = $canUpdate;
    }

    /**
     * @return bool|null
     */
    public function getCanDelete(): ?bool
    {
        return $this->canDelete;
    }

    /**
     * @param bool|null $canDelete
     */
    public function setCanDelete(?bool $canDelete): void
    {
        $this->canDelete = $canDelete;
    }

    /**
     * @return bool|null
     */
    public function getCanIndex(): ?bool
    {
        return $this->canIndex;
    }

    /**
     * @param bool|null $canIndex
     */
    public function setCanIndex(?bool $canIndex): void
    {
        $this->canIndex = $canIndex;
    }
}
