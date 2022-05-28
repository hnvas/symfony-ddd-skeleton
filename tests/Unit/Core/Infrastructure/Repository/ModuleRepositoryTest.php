<?php
declare(strict_types = 1);

namespace App\Tests\Unit\Core\Infrastructure\Repository;

use App\Core\Domain\Enum\UserRoleEnum;
use App\Core\Domain\Model\Module;
use App\Core\Domain\Model\User;
use App\Core\Domain\Repository\ModuleRepositoryInterface;
use App\Core\Infrastructure\Repository\ModuleRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ModuleRepositoryTest extends TestCase
{

    private MockObject                $entityManagerMock;
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

    public function testFindByUser()
    {
        $roles = [UserRoleEnum::ROLE_USER];
        $user  = new User('user@email', 'user', 'any', $roles);

        $expMock = self::createMock(Expr::class);
        $expMock->expects(self::once())
                ->method('in')
                ->willReturnSelf();

        $queryMock = self::createMock(AbstractQuery::class);
        $queryMock->expects(self::once())
                  ->method('getResult')
                  ->willReturn([]);

        $queryBuilderMock = self::createMock(QueryBuilder::class);
        $queryBuilderMock->expects(self::once())
                         ->method('select')
                         ->willReturnSelf();
        $queryBuilderMock->expects(self::once())
                         ->method('from')
                         ->willReturnSelf();
        $queryBuilderMock->expects(self::once())
                         ->method('expr')
                         ->willReturn($expMock);
        $queryBuilderMock->expects(self::once())
                         ->method('join')
                         ->willReturnSelf();
        $queryBuilderMock->expects(self::once())
                         ->method('where')
                         ->willReturnSelf();
        $queryBuilderMock->expects(self::once())
                         ->method('getQuery')
                         ->willReturn($queryMock);

        $this->entityManagerMock->expects(self::once())
                                ->method('createQueryBuilder')
                                ->willReturn($queryBuilderMock);

        $this->instance->findByUser($user);
    }
}
