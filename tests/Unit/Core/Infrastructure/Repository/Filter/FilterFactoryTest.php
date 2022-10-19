<?php
declare(strict_types = 1);

namespace App\Tests\Unit\Core\Infrastructure\Repository\Filter;

use App\Core\Domain\Model\Module;
use App\Core\Domain\Model\Permission;
use App\Core\Domain\Model\User;
use App\Core\Infrastructure\Repository\Filters\BaseFilter;
use App\Core\Infrastructure\Repository\Filters\FilterFactory;
use App\Core\Infrastructure\Repository\Filters\ModuleFilter;
use App\Core\Infrastructure\Repository\Filters\PermissionFilter;
use App\Core\Infrastructure\Repository\Filters\UserFilter;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Class FilterFactoryTest
 * @package App\Tests\Unit\Core\Infrastructure\Repository\Filter
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class FilterFactoryTest extends TestCase
{

    /**
     * @dataProvider provideRelatedFilters
     */
    public function testShouldCreateANewInstanceOfFilter(
        string $model,
        string $expected
    ) {
        $queryBuilderMock = self::createMock(QueryBuilder::class);
        $queryParams      = [];

        $filter = FilterFactory::create($queryParams, $queryBuilderMock, $model);

        self::assertInstanceOf($expected, $filter);
    }

    public function testShouldThrowsAnExceptionWhenEntityIsNotRelated()
    {
        $queryBuilderMock = self::createMock(QueryBuilder::class);
        $queryParams      = [];
        $model            = 'Any';

        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('No filter related to provided class');

        FilterFactory::create($queryParams, $queryBuilderMock, $model);
    }

    public function provideRelatedFilters(): array
    {
        return [
            [
                'model'    => User::class,
                'expected' => UserFilter::class
            ],
            [
                'model'    => Permission::class,
                'expected' => PermissionFilter::class
            ],
            [
                'model'    => Module::class,
                'expected' => ModuleFilter::class
            ]
        ];
    }

}
