<?php
declare(strict_types = 1);

namespace App\Tests\Unit\Core\Application\Query\UserPermission;

use App\Core\Application\Query\UserPermission\UserPermissionDTO;
use PHPUnit\Framework\TestCase;

class UserPermissionDTOTest extends TestCase
{

    public function testShouldCreateUserPermissionFromArrayDTO()
    {
        $data = [
            'module' => 'Core',
            'resource' => 'user_resource',
            'can_create' => true,
            'can_read' => true,
            'can_update' => true,
            'can_delete' => true,
            'can_index' => true
        ];

        $userPermission = UserPermissionDTO::createFromArray($data);

        self::assertInstanceOf(UserPermissionDTO::class, $userPermission);
        self::assertEquals($data['module'], $userPermission->getModule());
        self::assertEquals($data['resource'], $userPermission->getResource());
        self::assertEquals($data['can_create'], $userPermission->canCreate());
        self::assertEquals($data['can_read'], $userPermission->canRead());
        self::assertEquals($data['can_update'], $userPermission->canUpdate());
        self::assertEquals($data['can_delete'], $userPermission->canDelete());
        self::assertEquals($data['can_index'], $userPermission->canIndex());
    }
}
