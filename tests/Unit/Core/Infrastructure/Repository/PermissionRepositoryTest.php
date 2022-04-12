<?php
declare(strict_types = 1);

namespace App\Tests\Unit\Core\Infrastructure\Repository;

use App\Core\Domain\Enum\ModuleEnum;
use App\Core\Domain\Enum\UserRoleEnum;
use App\Core\Domain\Model\Module;
use App\Core\Domain\Model\Permission;
use App\Core\Domain\Repository\PermissionRepositoryInterface;
use App\Core\Infrastructure\Repository\PermissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PermissionRepositoryTest extends TestCase
{

    private MockObject $entityManagerMock;
    private PermissionRepositoryInterface $instance;

    public function setUp(): void
    {
        $classMetadataStub       = self::createStub(ClassMetadata::class);
        $classMetadataStub->name = Permission::class;

        $this->entityManagerMock = self::createMock(EntityManagerInterface::class);
        $this->entityManagerMock->method('getClassMetadata')
                          ->willReturn($classMetadataStub);

        $managerRegistryStub = self::createStub(ManagerRegistry::class);
        $managerRegistryStub
            ->method('getManagerForClass')
            ->willReturn($this->entityManagerMock);

        $this->instance = new PermissionRepository($managerRegistryStub);
    }

    public function testShouldAdd()
    {
        $this->entityManagerMock->expects(self::once())
                                ->method('persist');

        $this->instance->add($this->assemblyPermission());
    }

    public function testShouldRemove()
    {
        $this->entityManagerMock->expects(self::once())
                                ->method('remove');

        $this->instance->remove($this->assemblyPermission());
    }

    public function testShouldFind()
    {
        $this->entityManagerMock->expects(self::once())
                                ->method('find')
                                ->willReturn($this->assemblyPermission());

        $this->instance->findById(10);
    }

    public function testShouldCanGetEntityClassName()
    {
        $expected = Permission::class;

        self::assertEquals($expected, $this->instance->getEntityClassName());
    }

    public function testShouldFlush()
    {
        $this->entityManagerMock->expects(self::once())
                                ->method('flush');

        $this->instance->flush();
    }

    private function assemblyPermission(): Permission
    {
        return new Permission(
            UserRoleEnum::ROLE_USER,
            'user_resource',
            new Module(ModuleEnum::CORE),
            false,
            false,
            false,
            false,
            true
        );
    }
}
