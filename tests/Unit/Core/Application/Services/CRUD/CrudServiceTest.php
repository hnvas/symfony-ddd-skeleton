<?php
declare(strict_types = 1);

namespace App\Tests\Unit\Core\Application\Services\CRUD;

use App\Core\Application\Exceptions\InvalidDataException;
use App\Core\Application\Exceptions\ResourceNotFoundException;
use App\Core\Application\Services\CRUD\CrudService;
use App\Core\Domain\Enum\UserRoleEnum;
use App\Core\Domain\Functions\ClassName;
use App\Core\Domain\Model\Entity;
use App\Core\Domain\Model\Module;
use App\Core\Domain\Model\Permission;
use App\Core\Domain\Model\User;
use App\Core\Domain\Repository\EntityRepositoryInterface;
use App\Core\Domain\Repository\Pageable;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class CrudFacadeTest
 * @package App\Tests\Unit\Core\Application\Services
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class CrudServiceTest extends TestCase
{

    private ValidatorInterface        $validatorMock;
    private EntityRepositoryInterface $entityRepositoryMock;
    private CrudService               $service;

    protected function setUp(): void
    {
        $this->validatorMock        = self::createMock(ValidatorInterface::class);
        $this->entityRepositoryMock = self::createMock(
            EntityRepositoryInterface::class
        );

        $this->service = new CrudService(
            $this->entityRepositoryMock,
            $this->validatorMock
        );
    }

    /** @dataProvider entitiesToSave */
    public function testShouldSaveEntity(Entity $entity)
    {
        $violationsListMock = self::createMock(ConstraintViolationList::class);
        $violationsListMock->method('count')->willReturn(0);

        $this->validatorMock->expects(self::once())
                            ->method('validate')
                            ->with($entity)
                            ->willReturn($violationsListMock);

        $this->entityRepositoryMock->expects(self::once())
                                   ->method('add')
                                   ->with($entity);
        $this->entityRepositoryMock->expects(self::once())
                                   ->method('flush');

        $this->service->save($entity);
    }

    /** @dataProvider entitiesToSave */
    public function testShouldThrownExceptionWhenEntityIdIsNotUnique(Entity $entity)
    {
        $violationsListMock = self::createMock(ConstraintViolationList::class);
        $violationsListMock->method('count')->willReturn(0);

        $this->validatorMock->expects(self::once())
                            ->method('validate')
                            ->with($entity)
                            ->willReturn($violationsListMock);

        $uniqueExceptionMock = self::getMockBuilder(
            UniqueConstraintViolationException::class
        )->disableOriginalConstructor()
                                   ->disableOriginalClone()
                                   ->getMock();

        $this->entityRepositoryMock->expects(self::once())
                                   ->method('getEntityClassName')
                                   ->willReturn(get_class($entity));
        $this->entityRepositoryMock->expects(self::once())
                                   ->method('add')
                                   ->with($entity);
        $this->entityRepositoryMock->expects(self::once())
                                   ->method('flush')
                                   ->willThrowException($uniqueExceptionMock);

        $classname = ClassName::getBaseName(get_class($entity));
        $exMessage = "Provided values for $classname are not valid";
        self::expectExceptionMessage($exMessage);
        self::expectException(InvalidDataException::class);

        $this->service->save($entity);
    }

    /** @dataProvider entitiesToSave */
    public function testShouldThrownExceptionWhenEntityIsNotValid(Entity $entity)
    {
        $violationMock = self::createMock(ConstraintViolation::class);
        $violationMock->expects(self::once())
                      ->method('getPropertyPath')
                      ->willReturn('attribute');
        $violationMock->expects(self::once())
                      ->method('getMessage')
                      ->willReturn('invalid attribute');

        $violationsListMock = self::createMock(ConstraintViolationList::class);
        $violationsListMock->expects(self::once())
                           ->method('count')
                           ->willReturn(1);
        $violationsListMock->expects(self::once())
                           ->method('getIterator')
                           ->willReturn(new \ArrayIterator([$violationMock]));

        $this->validatorMock->expects(self::once())
                            ->method('validate')
                            ->with($entity)
                            ->willReturn($violationsListMock);

        $classname = ClassName::getBaseName(get_class($entity));
        $exMessage = "Provided values for $classname are not valid";
        self::expectExceptionMessage($exMessage);
        self::expectException(InvalidDataException::class);

        $this->service->save($entity);
    }

    /** @dataProvider entitiesToReadOrDelete */
    public function testShouldReadAnEntity(int $id, Entity $entity)
    {
        $this->entityRepositoryMock->expects(self::once())
                                   ->method('findById')
                                   ->willReturn($entity);

        $this->service->read($id);
    }

    /** @dataProvider entitiesToReadOrDelete */
    public function testShouldThrowAnExceptionWhenReadNotFound(int $id, Entity $entity)
    {
        $this->entityRepositoryMock->expects(self::once())
                                   ->method('getEntityClassName')
                                   ->willReturn(get_class($entity));
        $this->entityRepositoryMock->expects(self::once())
                                   ->method('findById')
                                   ->willReturn(null);

        $classname = ClassName::getBaseName(get_class($entity));
        self::expectExceptionMessage("The resource $classname was not found");
        self::expectException(ResourceNotFoundException::class);

        $this->service->read($id);
    }

    /** @dataProvider entitiesToReadOrDelete */
    public function testShouldDeleteAnEntity(int $id, Entity $entity)
    {
        $this->entityRepositoryMock->expects(self::once())
                                   ->method('findById')
                                   ->willReturn($entity);
        $this->entityRepositoryMock->expects(self::once())
                                   ->method('remove')
                                   ->with($entity);
        $this->entityRepositoryMock->expects(self::once())
                                   ->method('flush');

        $this->service->delete($id);
    }

    /** @dataProvider entitiesToReadOrDelete */
    public function testShouldThrowAnExceptionWhenDeleteNotFound(int $id, Entity $entity)
    {
        $this->entityRepositoryMock->expects(self::once())
                                   ->method('getEntityClassName')
                                   ->willReturn(get_class($entity));
        $this->entityRepositoryMock->expects(self::once())
                                   ->method('findById')
                                   ->willReturn(null);

        $classname = ClassName::getBaseName(get_class($entity));
        self::expectExceptionMessage("The resource $classname was not found");
        self::expectException(ResourceNotFoundException::class);

        $this->service->delete($id);
    }

    public function testShouldSearch()
    {
        $criteria = ['attribute' => 'value'];

        $pageableMock = self::createMock(Pageable::class);
        $this->entityRepositoryMock->expects(self::once())
                                   ->method('search')
                                   ->willReturn($pageableMock);

        $this->service->search($criteria);
    }

    public function entitiesToReadOrDelete(): array
    {
        [$module, $user, $permission] = $this->assemblyEntities();

        return [
            'user'       => ['id' => 10, 'entity' => $user],
            'permission' => ['id' => 3, 'entity' => $permission],
            'module'     => ['id' => 5, 'entity' => $module]
        ];
    }

    public function entitiesToSave(): array
    {
        [$module, $user, $permission] = $this->assemblyEntities();

        return [
            'user'       => [$user],
            'permission' => [$permission],
            'module'     => [$module]
        ];
    }

    private function assemblyEntities(): array
    {
        $module     = new Module('Core', true);
        $user       = new User('user@test.com', 'username', '123qwe456ert');
        $permission = new Permission(
            UserRoleEnum::ROLE_USER,
            'user_resource',
            $module
        );

        return [$module, $user, $permission];
    }
}
