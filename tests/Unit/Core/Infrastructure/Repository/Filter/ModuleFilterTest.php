<?php
declare(strict_types = 1);

namespace App\Tests\Unit\Core\Infrastructure\Repository\Filter;

use App\Core\Domain\Enum\UserRoleEnum;
use App\Core\Domain\Model\Module;
use App\Core\Infrastructure\Repository\Filters\FilterFactory;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class ModuleFilterTest
 * @package App\Tests\Unit\Core\Infrastructure\Repository\Filter
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class ModuleFilterTest extends TestCase
{

    private MockObject $queryBuilderMock;

    public function setUp(): void
    {
        $this->queryBuilderMock = self::createMock(QueryBuilder::class);
        $this->queryBuilderMock
            ->method('getRootAliases')
            ->willReturn(['e']);
    }

    public function testShouldApplyRoleFilter(): void
    {
        $expMock = self::createMock(Expr::class);
        $expMock->expects(self::once())
            ->method('in')
            ->willReturnSelf();
        $this->queryBuilderMock
            ->expects(self::once())
            ->method('expr')
            ->willReturn($expMock);
        $this->queryBuilderMock
            ->expects(self::once())
            ->method('join')
            ->willReturnSelf();
        $this->queryBuilderMock
            ->expects(self::once())
            ->method('andWhere')
            ->willReturnSelf();

        $params = ['roles' => [UserRoleEnum::ROLE_USER]];
        $filter = FilterFactory::create(
            $params,
            $this->queryBuilderMock,
            Module::class
        );

        $filter->apply();
    }

}
