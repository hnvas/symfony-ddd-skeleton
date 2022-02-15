<?php
declare(strict_types = 1);

namespace App\Core\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;
use App\Core\Infrastructure\Repository\PermissionRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Serializer\XmlRoot("permission")
 *
 * @Hateoas\Relation("self", href = "expr('/api/permission/' ~ object.getId())")
 */
class Permission extends Entity
{

    /**
     * @Serializer\XmlAttribute
     *
     * @var int|null
     */
    private ?int $id;

    /**
     * @Assert\NotBlank
     *
     * @var string|null
     */
    private ?string $role;

    /**
     * @Assert\NotBlank
     *
     * @var string|null
     */
    private ?string $resource;

    /**
     * @Assert\NotBlank
     *
     * @var bool|null
     */
    private ?bool $canCreate;

    /**
     * @Assert\NotBlank
     *
     * @var bool|null
     */
    private ?bool $canRead;

    /**
     * @Assert\NotBlank
     *
     * @var bool|null
     */
    private ?bool $canUpdate;

    /**
     * @Assert\NotBlank
     *
     * @var bool|null
     */
    private ?bool $canDelete;

    /**
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
    public function canCreate(): ?bool
    {
        return $this->canCreate;
    }

    /**
     * @return bool|null
     */
    public function canRead(): ?bool
    {
        return $this->canRead;
    }

    /**
     * @return bool|null
     */
    public function canUpdate(): ?bool
    {
        return $this->canUpdate;
    }

    /**
     * @return bool|null
     */
    public function canDelete(): ?bool
    {
        return $this->canDelete;
    }

    /**
     * @return bool|null
     */
    public function canIndex(): ?bool
    {
        return $this->canIndex;
    }
}
