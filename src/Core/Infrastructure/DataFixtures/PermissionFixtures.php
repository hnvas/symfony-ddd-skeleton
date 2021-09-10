<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\DataFixtures;

use App\Core\Domain\Model\Permission;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PermissionFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $permissions = [
            'role'     => 'ROLE_USER',
            'resource' => 'api/user',
            'create'   => false,
            'read'     => true,
            'update'   => true,
            'delete'   => false,
            'index'    => true
        ];

        foreach ($permissions as $permissionValues) {
            $permission = new Permission();
            $permission->setRole($permissionValues['role']);
            $permission->setResource($permissionValues['resource']);
            $permission->setCreate($permissionValues['create']);
            $permission->setRead($permissionValues['read']);
            $permission->setUpdate($permissionValues['update']);
            $permission->setDelete($permissionValues['delete']);
            $permission->setIndex($permissionValues['index']);
        }
    }
}
