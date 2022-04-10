<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Repository\Filters;

use Doctrine\ORM\Query\Expr\Join;

class ModuleFilter extends BaseFilter
{
    public function roles(array $roles): void
    {
        $rootAlias = current($this->builder->getRootAliases());
        $exp = $this->builder->expr()->in('p.role', $roles);
        $this->builder
            ->join("$rootAlias.permissions", 'p', Join::WITH)
            ->andWhere($exp);
    }
}
