<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Repository\Filters;

/**
 * Class UserFilter
 * @package App\Core\Infrastructure\Repository\Filters
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class UserFilter extends BaseFilter
{

    public function email(string $value): void
    {
        $alias = $this->queryAlias('email');
        $exp   = $this->builder->expr()
                               ->like($alias, ':value');

        $this->builder->andWhere($exp)
                      ->setParameter('value', "%$value%");
    }

    public function name(string $value): void
    {
        $alias = $this->queryAlias('name');
        $exp   = $this->builder->expr()
                               ->like($alias, ':value');

        $this->builder->andWhere($exp)
                      ->setParameter('value', "%$value%");
    }

}
