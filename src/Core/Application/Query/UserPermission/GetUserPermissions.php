<?php
declare(strict_types = 1);

namespace App\Core\Application\Query\UserPermission;

use App\Core\Domain\Model\User;

class GetUserPermissions
{

    private UserPermissionDAO $userPermissionDAO;

    /**
     * @param \App\Core\Application\Query\UserPermission\UserPermissionDAO $userPermissionDAO
     */
    public function __construct(UserPermissionDAO $userPermissionDAO)
    {
        $this->userPermissionDAO = $userPermissionDAO;
    }

    /**
     * @param \App\Core\Domain\Model\User $user
     *
     * @return array
     * @throws \Doctrine\DBAL\Exception
     */
    public function execute(User $user): array
    {
        $resultSet = $this->userPermissionDAO->findPermissionsByUserRoles($user);

        return array_map(
            fn($permission) => UserPermissionDTO::createFromArray($permission),
            $resultSet
        );
    }
}
