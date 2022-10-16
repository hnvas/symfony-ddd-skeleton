<?php
declare(strict_types = 1);

namespace App\Tests\Unit\Core\Application\Query\UserPermission;

use App\Core\Application\Query\UserPermission\UserPermissionDAO;
use App\Core\Domain\Enum\UserRoleEnum;
use App\Core\Domain\Model\User;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use PHPUnit\Framework\TestCase;

class UserPermissionDAOTest extends TestCase
{

    public function testShouldFindPermissionsByUserRoles()
    {
        $roles = [UserRoleEnum::ROLE_USER];
        $user  = new User('user@email', 'user', 'any', $roles);

        $queryBuilderMock = self::createMock(QueryBuilder::class);
        $queryBuilderMock->expects(self::once())
                         ->method('select')
                         ->willReturnSelf();
        $queryBuilderMock->expects(self::once())
                         ->method('from')
                         ->willReturnSelf();
        $queryBuilderMock->expects(self::once())
                         ->method('join')
                         ->willReturnSelf();
        $queryBuilderMock->expects(self::once())
                         ->method('where')
                         ->willReturnSelf();
        $queryBuilderMock->expects(self::once())
                         ->method('andWhere')
                         ->willReturnSelf();
        $queryBuilderMock->expects(self::once())
                         ->method('groupBy')
                         ->willReturnSelf();
        $queryBuilderMock->expects(self::once())
                         ->method('getSQL')
                         ->willReturn('querySQL');

        $connectionMock = $this->createMock(Connection::class);
        $connectionMock->expects(self::once())
                       ->method('createQueryBuilder')
                       ->willReturn($queryBuilderMock);
        $connectionMock->expects(self::once())
                       ->method('fetchAllAssociative')
                       ->willReturn([]);

        $userPermissionDAO = new UserPermissionDAO($connectionMock);

        $userPermissionDAO->findPermissionsByUserRoles($user);
    }
}
