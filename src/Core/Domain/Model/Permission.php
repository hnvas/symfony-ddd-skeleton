<?php
declare(strict_types = 1);

namespace App\Core\Domain\Model;

/**
 * Class Permission
 * @package App\Core\Domain\Model
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class Permission implements Entity
{
    private ?int   $id;
    private string $role;
    private Module $module;
    private string $resource;
    private bool   $canCreate;
    private bool   $canRead;
    private bool   $canUpdate;
    private bool   $canDelete;
    private bool   $canIndex;

    public function __construct(
        string $role,
        string $resource,
        Module $module,
        bool  $canCreate = false,
        bool  $canRead = false,
        bool  $canUpdate = false,
        bool  $canDelete = false,
        bool  $canIndex = false
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
    public function id(): ?int
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
