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
            [
                'role'     => 'ROLE_USER',
                'resource' => '/api/user/',
                'create'   => false,
                'read'     => true,
                'update'   => true,
                'delete'   => false,
                'index'    => true
            ],
            [
                'role'     => 'ROLE_USER',
                'resource' => '/api/permission/',
                'create'   => false,
                'read'     => false,
                'update'   => false,
                'delete'   => false,
                'index'    => true
            ]
        ];

        foreach ($permissions as $permissionValues) {
            $permission = new Permission();
            $permission->setRole($permissionValues['role']);
            $permission->setResource($permissionValues['resource']);
            $permission->setCanCreate($permissionValues['create']);
            $permission->setCanRead($permissionValues['read']);
            $permission->setCanUpdate($permissionValues['update']);
            $permission->setCanDelete($permissionValues['delete']);
            $permission->setCanIndex($permissionValues['index']);

            $manager->persist($permission);
        }

        $manager->flush();
    }
}
