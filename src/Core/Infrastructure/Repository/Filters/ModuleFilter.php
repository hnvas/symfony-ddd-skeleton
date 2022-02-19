<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Repository\Filters;

use Doctrine\ORM\Query\Expr\Join;

class ModuleFilter extends BaseFilter
{

    public function roles(array $roles): void
    {
        $alias = $this->queryAlias('roles');
        $exp   = $this->builder->expr()->in($alias, $roles);

        $this->builder
            ->join('m.permissions', 'p', Join::WITH)
            ->andWhere($exp);
    }
}
