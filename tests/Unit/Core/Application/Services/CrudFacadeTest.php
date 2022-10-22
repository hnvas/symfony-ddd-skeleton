<?php
declare(strict_types = 1);

namespace App\Tests\Unit\Core\Application\Services;

use App\Core\Application\Exceptions\InvalidDataException;
use App\Core\Application\Services\Crud\CrudFacade;
use App\Core\Domain\Enum\UserRoleEnum;
use App\Core\Domain\Functions\ClassName;
use App\Core\Domain\Model\Entity;
use App\Core\Domain\Model\Module;
use App\Core\Domain\Model\Permission;
use App\Core\Domain\Model\User;
use App\Core\Domain\Repository\EntityRepositoryInterface;
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
class CrudFacadeTest extends TestCase
{

    private ValidatorInterface        $validatorMock;
    private EntityRepositoryInterface $entityRepositoryMock;

    protected function setUp(): void
    {
        $this->validatorMock        = self::createMock(ValidatorInterface::class);
        $this->entityRepositoryMock = self::createMock(
            EntityRepositoryInterface::class
        );
    }

    /** @dataProvider entitiesProvider */
    public function testShouldSaveEntity(Entity $entity)
    {
        $violationsListMock = self::createMock(ConstraintViolationList::class);
        $violationsListMock->method('count')->willReturn(0);

        $this->validatorMock->expects(self::once())
                            ->method('validate')
                            ->with($entity)
                            ->willReturn($violationsListMock);

        $this->entityRepositoryMock->expects(self::once())
                                   ->method('getEntityClassName')
                                   ->willReturn(get_class($entity));
        $this->entityRepositoryMock->expects(self::once())
                                   ->method('add')
                                   ->with($entity);
        $this->entityRepositoryMock->expects(self::once())
                                   ->method('flush');

        $service = new CrudFacade(
            $this->entityRepositoryMock,
            $this->validatorMock
        );

        $service->save($entity);
    }

    /** @dataProvider entitiesProvider */
    public function testShouldThrownExceptionWhenEntityIsNotValid(Entity $entity)
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

        $service = new CrudFacade(
            $this->entityRepositoryMock,
            $this->validatorMock
        );

        $service->save($entity);
    }

    /** @dataProvider entitiesProvider */
    public function testShouldThrownExceptionWhenEntityIdIsNotUnique(Entity $entity)
    {
        $this->entityRepositoryMock->expects(self::once())
                                   ->method('getEntityClassName')
                                   ->willReturn(get_class($entity));

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

        $service = new CrudFacade(
            $this->entityRepositoryMock,
            $this->validatorMock
        );

        $service->save($entity);
    }

    public function entitiesProvider(): array
    {
        $module     = new Module('Core', true);
        $user       = new User('user@test.com', 'username', '123qwe456ert');
        $permission = new Permission(
            UserRoleEnum::ROLE_USER,
            'user_resource',
            $module
        );

        return [
            ['user' => $user],
            ['permission' => $permission],
            ['module' => $module]
        ];
    }
}
