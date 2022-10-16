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
            'sum(can_create::int)::int::bool as can_create',
            'sum(can_read::int)::int::bool   as can_read',
            'sum(can_update::int)::int::bool as can_update',
            'sum(can_delete::int)::int::bool as can_delete',
            'sum(can_index::int)::int::bool  as can_index'
        ])->from('public.module', 'm')
          ->join('m', 'public.permission', 'p', 'm.id = p.module_id')
          ->where('m.enabled')
          ->andWhere('p.role in (:roles)')
          ->groupBy(['m.name', 'p.resource']);

        return $this->connection->fetchAllAssociative(
            $builder->getSQL(),
            ['roles' => $user->roles()]
        );
    }
}
