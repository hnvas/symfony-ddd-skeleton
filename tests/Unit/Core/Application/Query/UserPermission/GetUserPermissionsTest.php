<?php
declare(strict_types = 1);

namespace App\Tests\Unit\Core\Application\Query\UserPermission;

use App\Core\Application\Query\UserPermission\GetUserPermissions;
use App\Core\Application\Query\UserPermission\UserPermissionDAO;
use App\Core\Application\Query\UserPermission\UserPermissionDTO;
use App\Core\Domain\Enum\UserRoleEnum;
use App\Core\Domain\Model\User;
use PHPUnit\Framework\TestCase;

class GetUserPermissionsTest extends TestCase
{

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function testShouldGetUserPermissions()
    {
        $roles     = [UserRoleEnum::ROLE_USER];
        $user      = new User('user@email', 'user', 'any', $roles);
        $DAOReturn = [
            [
                'module'     => 'Core',
                'resource'   => 'user_resources',
                'can_create' => true,
                'can_read'   => true,
                'can_update' => true,
                'can_delete' => true,
                'can_index'  => true
            ],
            [
                'module'     => 'Core',
                'resource'   => 'user_resources',
                'can_create' => true,
                'can_read'   => true,
                'can_update' => true,
                'can_delete' => true,
                'can_index'  => true
            ]
        ];

        $mockUserPermissionDAO = self::createMock(UserPermissionDAO::class);
        $mockUserPermissionDAO->expects(self::once())
                              ->method('findPermissionsByUserRoles')
                              ->with($user)
                              ->willReturn($DAOReturn);

        $getUserPermissions = new GetUserPermissions($mockUserPermissionDAO);
        $result             = $getUserPermissions->execute($user);
        $first              = current($result);

        self::assertIsArray($result);
        self::assertInstanceOf(UserPermissionDTO::class, $first);
    }
}
