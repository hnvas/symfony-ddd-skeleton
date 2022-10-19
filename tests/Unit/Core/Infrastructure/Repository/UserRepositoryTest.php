<?php
declare(strict_types = 1);

namespace App\Tests\Unit\Core\Infrastructure\Repository;

use App\Core\Domain\Model\User;
use App\Core\Domain\Repository\UserRepositoryInterface;
use App\Core\Infrastructure\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Persisters\Entity\BasicEntityPersister;
use Doctrine\ORM\UnitOfWork;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

/**
 * Class UserRepositoryTest
 * @package App\Tests\Unit\Core\Infrastructure\Repository
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class UserRepositoryTest extends TestCase
{

    private MockObject              $entityManagerMock;
    private MockObject              $passwordHasherMock;
    private UserRepositoryInterface $instance;

    public function setUp(): void
    {
        $classMetadataStub       = self::createStub(ClassMetadata::class);
        $classMetadataStub->name = User::class;

        $this->entityManagerMock = self::createMock(EntityManagerInterface::class);
        $this->entityManagerMock->method('getClassMetadata')
                                ->willReturn($classMetadataStub);

        $managerRegistryStub = self::createStub(ManagerRegistry::class);
        $managerRegistryStub
            ->method('getManagerForClass')
            ->willReturn($this->entityManagerMock);

        $this->passwordHasherMock = self::createMock(
            UserPasswordHasher::class
        );

        $this->instance = new UserRepository(
            $managerRegistryStub,
            $this->passwordHasherMock
        );
    }

    public function testShouldAdd()
    {
        $user = $this->assemblyUser();
        $this->passwordHasherMock
            ->expects(self::once())
            ->method('hashPassword')
            ->willReturn('password');
        $this->entityManagerMock
            ->expects(self::once())
            ->method('persist');

        $this->instance->add($user);
    }

    public function testShouldRemove()
    {
        $this->entityManagerMock->expects(self::once())
                                ->method('remove');

        $this->instance->remove($this->assemblyUser());
    }

    public function testShouldFind()
    {
        $this->entityManagerMock->expects(self::once())
                                ->method('find')
                                ->willReturn($this->assemblyUser());

        $this->instance->findById(10);
    }

    public function testShouldCanGetEntityClassName()
    {
        $expected = User::class;

        self::assertEquals($expected, $this->instance->getEntityClassName());
    }

    public function testShouldFlush()
    {
        $this->entityManagerMock->expects(self::once())
                                ->method('flush');

        $this->instance->flush();
    }

    public function testShouldLoadUserByIdentifier()
    {
        $user                     = $this->assemblyUser();
        $basicEntityPersisterStub = self::createStub(BasicEntityPersister::class);
        $basicEntityPersisterStub
            ->method('load')
            ->willReturn($user);

        $unitOfWorkMock = self::createMock(UnitOfWork::class);
        $unitOfWorkMock
            ->expects(self::once())
            ->method('getEntityPersister')
            ->willReturn($basicEntityPersisterStub);

        $this->entityManagerMock
            ->expects(self::once())
            ->method('getUnitOfWork')
            ->willReturn($unitOfWorkMock);

        $this->instance->loadUserByIdentifier($user->email());
    }

    private function assemblyUser(): User
    {
        return new User(
            'user@email.com',
            'user',
            'any'
        );
    }
}
