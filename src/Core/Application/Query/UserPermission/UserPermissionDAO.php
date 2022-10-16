<?php
declare(strict_types = 1);

namespace App\Core\Application\Query\UserPermission;

use App\Core\Domain\Model\User;
use Doctrine\DBAL\Connection;

class UserPermissionDAO
{

    private Connection $connection;

    /**
     * @param \Doctrine\DBAL\Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param \App\Core\Domain\Model\User $user
     *
     * @return array
     * @throws \Doctrine\DBAL\Exception
     */
    public function findPermissionsByUserRoles(User $user): array
    {
        $builder = $this->connection->createQueryBuilder();
        $builder->select([
            'm.name as module',
            'p.resource as resource',
            'cast(cast(sum(cast(can_create as int)) as int) as bool) as can_create',
            'cast(cast(sum(cast(can_read as int)) as int) as bool) as can_read',
            'cast(cast(sum(cast(can_update as int)) as int) as bool) as can_update',
            'cast(cast(sum(cast(can_delete as int)) as int) as bool) as can_delete',
            'cast(cast(sum(cast(can_index as int)) as int) as bool)  as can_index'
        ])->from('module', 'm')
          ->join('m', 'permission', 'p', 'm.id = p.module_id')
          ->where('m.enabled = true')
          ->andWhere($builder->expr()->in('p.role', ':roles'))
          ->setParameter('roles', $user->roles(), Connection::PARAM_STR_ARRAY)
          ->groupBy(['m.name', 'p.resource']);

        return $this->connection->fetchAllAssociative(
            $builder->getSQL(),
            $builder->getParameters(),
            $builder->getParameterTypes()
        );
    }
}
