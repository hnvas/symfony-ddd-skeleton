<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Repository\Filters;

use App\Core\Domain\Model\Module;
use App\Core\Domain\Model\Permission;
use App\Core\Domain\Model\User;
use Doctrine\ORM\QueryBuilder;
use InvalidArgumentException;

/**
 * Class FilterFactory
 * @package App\Core\Infrastructure\Repository\Filters
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class FilterFactory
{

    const BIND_RELATED = [
        User::class       => UserFilter::class,
        Permission::class => PermissionFilter::class,
        Module::class     => ModuleFilter::class
    ];

    public static function create(
        array        $params,
        QueryBuilder $queryBuilder,
        string       $className
    ): BaseFilter {
        if (!self::hasRelatedFilter($className)) {
            throw new InvalidArgumentException(
                'No filter related to provided class'
            );
        }

        $class = self::BIND_RELATED[ $className ];

        return new $class($params, $queryBuilder);
    }

    private static function hasRelatedFilter(string $className): bool
    {
        return array_key_exists($className, self::BIND_RELATED);
    }
}
