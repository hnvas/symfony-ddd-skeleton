<?php
declare(strict_types = 1);

namespace App\Tests\Unit\Core\Application\Query\UserPermission;

use App\Core\Application\Query\UserPermission\UserPermissionDAO;
use App\Core\Domain\Enum\UserRoleEnum;
use App\Core\Domain\Model\User;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\Query\Expr;
use PHPUnit\Framework\TestCase;

/**
 * Class UserPermissionDAOTest
 * @package App\Tests\Unit\Core\Application\Query\UserPermission
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class UserPermissionDAOTest extends TestCase
{

    public function testShouldFindPermissionsByUserRoles()
    {
        $roles = [UserRoleEnum::ROLE_USER];
        $user  = new User('user@email', 'user', 'any', $roles);

        $expMock = self::createMock(Expr::class);
        $expMock->expects(self::once())
                ->method('in')
                ->willReturnSelf();

        $queryBuilderMock = self::createMock(QueryBuilder::class);
        $queryBuilderMock->expects(self::once())
                         ->method('expr')
                         ->willReturn($expMock);
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
                         ->method('setParameter')
                         ->willReturnSelf();
        $queryBuilderMock->expects(self::once())
                         ->method('groupBy')
                         ->willReturnSelf();
        $queryBuilderMock->expects(self::once())
                         ->method('getSQL')
                         ->willReturn('querySQL');
        $queryBuilderMock->expects(self::once())
                         ->method('getParameters')
                         ->willReturn([]);
        $queryBuilderMock->expects(self::once())
                         ->method('getParameterTypes')
                         ->willReturn([]);

        $connectionMock = $this->createMock(Connection::class);
        $connectionMock->expects(self::once())
                       ->method('createQueryBuilder')
                       ->willReturn($queryBuilderMock);
        $connectionMock->expects(self::once())
                       ->method('fetchAllAssociative')
                       ->with()
                       ->willReturn([]);

        $userPermissionDAO = new UserPermissionDAO($connectionMock);

        $userPermissionDAO->findPermissionsByUserRoles($user);
    }
}
