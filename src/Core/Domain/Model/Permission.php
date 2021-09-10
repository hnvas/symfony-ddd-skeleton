<?php
declare(strict_types = 1);

namespace App\Core\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;
use App\Core\Infrastructure\Repository\PermissionRepository;

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
     *
     * @var string|null
     */
    private ?string $role;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @var string|null
     */
    private ?string $resource;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool|null
     */
    private ?bool $create;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool|null
     */
    private ?bool $read;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool|null
     */
    private ?bool $update;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool|null
     */
    private ?bool $delete;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool|null
     */
    private ?bool $index;

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
    public function getCreate(): ?bool
    {
        return $this->create;
    }

    /**
     * @param bool|null $create
     */
    public function setCreate(?bool $create): void
    {
        $this->create = $create;
    }

    /**
     * @return bool|null
     */
    public function getRead(): ?bool
    {
        return $this->read;
    }

    /**
     * @param bool|null $read
     */
    public function setRead(?bool $read): void
    {
        $this->read = $read;
    }

    /**
     * @return bool|null
     */
    public function getUpdate(): ?bool
    {
        return $this->update;
    }

    /**
     * @param bool|null $update
     */
    public function setUpdate(?bool $update): void
    {
        $this->update = $update;
    }

    /**
     * @return bool|null
     */
    public function getDelete(): ?bool
    {
        return $this->delete;
    }

    /**
     * @param bool|null $delete
     */
    public function setDelete(?bool $delete): void
    {
        $this->delete = $delete;
    }

    /**
     * @return bool|null
     */
    public function getIndex(): ?bool
    {
        return $this->index;
    }

    /**
     * @param bool|null $index
     */
    public function setIndex(?bool $index): void
    {
        $this->index = $index;
    }
}
