<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Repository\Filters;

use Doctrine\ORM\Query\Expr\Join;

class ModuleFilter extends BaseFilter
{

    public function roles(array $roles): void
    {
        $this->builder
            ->join('m.permissions', 'p', Join::WITH)
            ->andWhere($this->builder->expr()->in('p.role', $roles));
    }

}
