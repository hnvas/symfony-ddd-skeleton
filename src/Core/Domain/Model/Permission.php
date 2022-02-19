<?php
declare(strict_types = 1);

namespace App\Core\Domain\Model;

use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;
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
     * @Assert\NotNull
     *
     * @var \App\Core\Domain\Model\Module|null
     */
    private ?Module $module;

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

    public function __construct(
        string $role,
        string $resource,
        Module $module,
        ?bool  $canCreate = false,
        ?bool  $canRead = false,
        ?bool  $canUpdate = false,
        ?bool  $canDelete = false,
        ?bool  $canIndex = false
    ) {
        $this->role      = $role;
        $this->resource  = $resource;
        $this->module    = $module;
        $this->canCreate = $canCreate;
        $this->canRead   = $canRead;
        $this->canUpdate = $canUpdate;
        $this->canDelete = $canDelete;
        $this->canIndex  = $canIndex;
    }

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
    public function role(): ?string
    {
        return $this->role;
    }

    /**
     * @return string|null
     */
    public function resource(): ?string
    {
        return $this->resource;
    }

    /**
     * @return Module|null
     */
    public function module(): ?Module
    {
        return $this->module;
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
