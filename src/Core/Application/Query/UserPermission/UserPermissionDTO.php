<?php
declare(strict_types = 1);

namespace App\Core\Application\Query\UserPermission;

/**
 * Class UserPermissionDTO
 * @package App\Core\Application\Query\UserPermission
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class UserPermissionDTO
{

    private string $module;
    private string $resource;
    private bool   $canCreate;
    private bool   $canRead;
    private bool   $canUpdate;
    private bool   $canDelete;
    private bool   $canIndex;

    public function __construct(
        string $module,
        string $resource,
        bool   $canCreate,
        bool   $canRead,
        bool   $canUpdate,
        bool   $canDelete,
        bool   $canIndex
    ) {
        $this->module    = $module;
        $this->resource  = $resource;
        $this->canCreate = $canCreate;
        $this->canRead   = $canRead;
        $this->canUpdate = $canUpdate;
        $this->canDelete = $canDelete;
        $this->canIndex  = $canIndex;
    }

    public static function createFromArray(array $userPermissionData): self
    {
        return new self(
            $userPermissionData['module'],
            $userPermissionData['resource'],
            $userPermissionData['can_create'],
            $userPermissionData['can_read'],
            $userPermissionData['can_update'],
            $userPermissionData['can_delete'],
            $userPermissionData['can_index']
        );
    }

    /**
     * @return string
     */
    public function getModule(): string
    {
        return $this->module;
    }

    /**
     * @return string
     */
    public function getResource(): string
    {
        return $this->resource;
    }

    /**
     * @return bool
     */
    public function canCreate(): bool
    {
        return $this->canCreate;
    }

    /**
     * @return bool
     */
    public function canRead(): bool
    {
        return $this->canRead;
    }

    /**
     * @return bool
     */
    public function canUpdate(): bool
    {
        return $this->canUpdate;
    }

    /**
     * @return bool
     */
    public function canDelete(): bool
    {
        return $this->canDelete;
    }

    /**
     * @return bool
     */
    public function canIndex(): bool
    {
        return $this->canIndex;
    }
}
