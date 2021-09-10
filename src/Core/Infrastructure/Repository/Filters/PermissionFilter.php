<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Repository\Filters;

class PermissionFilter extends BaseFilter
{

    public function role(string $role): void
    {
        $alias = $this->queryAlias('role');
        $exp   = $this->builder->expr()->like($alias, ':value');

        $this->builder->andWhere($exp)->setParameter('value', "%$role%");
    }

}
