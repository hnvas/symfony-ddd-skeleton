<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Repository\Filters;

use App\Core\Domain\Model\Module;
use App\Core\Domain\Model\Permission;
use App\Core\Domain\Model\User;
use Doctrine\ORM\QueryBuilder;

class FilterFactory
{

    const BIND_RELATED = [
        User::class       => UserFilter::class,
        Permission::class => PermissionFilter::class
    ];

    public static function create(
        array        $params,
        QueryBuilder $queryBuilder,
        string       $className
    ): BaseFilter {
        $class = self::BIND_RELATED[$className] ?? BaseFilter::class;

        return new $class($params, $queryBuilder);
    }
}
