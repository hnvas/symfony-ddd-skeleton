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
                'resource' => 'user_resource',
                'create'   => false,
                'read'     => true,
                'update'   => true,
                'delete'   => false,
                'index'    => true
            ],
            [
                'role'     => 'ROLE_USER',
                'resource' => 'permission_resource',
                'create'   => false,
                'read'     => false,
                'update'   => false,
                'delete'   => false,
                'index'    => true
            ]
        ];

        foreach ($permissions as $permissionValues) {
            $permission = new Permission(
                $permissionValues['role'],
                $permissionValues['resource'],
                $permissionValues['create'],
                $permissionValues['read'],
                $permissionValues['update'],
                $permissionValues['delete'],
                $permissionValues['index']
            );

            $manager->persist($permission);
        }

        $manager->flush();
    }
}
