<?php
declare(strict_types = 1);

namespace App\Core\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Serializer\XmlRoot("module")
 *
 * @Hateoas\Relation("self", href = "expr('/api/module/' ~ object.getId())")
 */
class Module extends Entity
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
     * @var string
     */
    private string $name;

    /**
     * @var bool
     */
    private bool $enabled = false;

    /**
     * @var Collection<Permission>|null
     */
    private ?Collection $permissions;

    public function __construct(string $name, bool $enabled = false){
        $this->name    = $name;
        $this->enabled = $enabled;
        $this->permissions = new ArrayCollection([]);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setPermissions(Collection $permissions): void
    {
        $this->permissions = $permissions;
    }

    public function addPermission(Permission $permission): void
    {
        $this->permissions->add($permission);
    }

    public function permissions(): ?Collection
    {
        return $this->permissions;
    }
}
