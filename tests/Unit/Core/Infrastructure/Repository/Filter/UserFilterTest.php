<?php
declare(strict_types = 1);

namespace App\Tests\Unit\Core\Infrastructure\Repository\Filter;

use App\Core\Domain\Model\User;
use App\Core\Infrastructure\Repository\Filters\FilterFactory;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class UserFilterTest
 * @package App\Tests\Unit\Core\Infrastructure\Repository\Filter
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class UserFilterTest extends TestCase
{

    private MockObject $queryBuilderMock;

    public function setUp(): void
    {
        $this->queryBuilderMock = self::createMock(QueryBuilder::class);
        $this->queryBuilderMock
            ->method('getRootAliases')
            ->willReturn(['e']);
    }

    public function testShouldApplyNameAndEmailFilter(): void
    {
        $expMock = self::createMock(Expr::class);
        $expMock->expects(self::exactly(2))
            ->method('like')
            ->willReturnSelf();
        $this->queryBuilderMock
            ->expects(self::exactly(2))
            ->method('expr')
            ->willReturn($expMock);
        $this->queryBuilderMock
            ->expects(self::exactly(2))
            ->method('andWhere')
            ->willReturnSelf();
        $this->queryBuilderMock
            ->expects(self::exactly(2))
            ->method('setParameter')
            ->willReturnSelf();

        $params = ['name' => 'Admin', 'email' => 'user@admin.com'];
        $filter = FilterFactory::create(
            $params,
            $this->queryBuilderMock,
            User::class
        );

        $filter->apply();
    }
}
