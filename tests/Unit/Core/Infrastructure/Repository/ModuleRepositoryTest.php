<?php
declare(strict_types = 1);

namespace App\Tests\Unit\Core\Infrastructure\Repository;

use App\Core\Domain\Model\Module;
use App\Core\Domain\Repository\ModuleRepositoryInterface;
use App\Core\Infrastructure\Repository\ModuleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ModuleRepositoryTest extends TestCase
{

    private MockObject $entityManagerMock;
    private ModuleRepositoryInterface $instance;

    public function setUp(): void
    {
        $classMetadataStub       = self::createStub(ClassMetadata::class);
        $classMetadataStub->name = Module::class;

        $this->entityManagerMock = self::createMock(EntityManagerInterface::class);
        $this->entityManagerMock->method('getClassMetadata')
                          ->willReturn($classMetadataStub);

        $managerRegistryStub = self::createStub(ManagerRegistry::class);
        $managerRegistryStub
            ->method('getManagerForClass')
            ->willReturn($this->entityManagerMock);

        $this->instance = new ModuleRepository($managerRegistryStub);

    }

    public function testShouldAdd()
    {
        $this->entityManagerMock->expects(self::once())
                                ->method('persist');

        $this->instance->add(new Module('Any', true));
    }

    public function testShouldRemove()
    {
        $this->entityManagerMock->expects(self::once())
                                ->method('remove');

        $this->instance->remove(new Module('Any', true));
    }

    public function testShouldFind()
    {
        $this->entityManagerMock->expects(self::once())
                                ->method('find')
                                ->willReturn(new Module('Any', true));

        $this->instance->findById(10);
    }

    public function testShouldCanGetEntityClassName()
    {
        $expected = Module::class;

        self::assertEquals($expected, $this->instance->getEntityClassName());
    }

    public function testShouldFlush()
    {
        $this->entityManagerMock->expects(self::once())
                                ->method('flush');

        $this->instance->flush();
    }
}
