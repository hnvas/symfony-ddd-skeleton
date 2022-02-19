<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\DataFixtures;

use App\Core\Domain\Model\Module;
use App\Core\Domain\Model\Permission;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PermissionFixtures extends Fixture implements
    DependentFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        /** @var Module $module */
        $module = $this->getReference(ModuleFixtures::CORE_MODULE_REFERENCE);

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
                $module,
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

    public function getDependencies()
    {
        return [
            ModuleFixtures::class
        ];
    }

    public static function getGroups(): array
    {
        return ['permission'];
    }
}
