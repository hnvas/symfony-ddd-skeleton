<?php
declare(strict_types = 1);

namespace App\Tests\Unit\Core\Infrastructure\Repository\Filter;

use App\Core\Domain\Model\Module;
use App\Core\Domain\Model\Permission;
use App\Core\Domain\Model\User;
use App\Core\Infrastructure\Repository\Filters\BaseFilter;
use App\Core\Infrastructure\Repository\Filters\FilterFactory;
use App\Core\Infrastructure\Repository\Filters\PermissionFilter;
use App\Core\Infrastructure\Repository\Filters\UserFilter;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;

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

    public function provideRelatedFilters(): array
    {
        return [
            'related'   => [
                'model'    => User::class,
                'expected' => UserFilter::class
            ],
            'variation' => [
                'model'    => Permission::class,
                'expected' => PermissionFilter::class
            ],
            'default'   => [
                'model'    => Module::class,
                'expected' => BaseFilter::class
            ]
        ];
    }

}
