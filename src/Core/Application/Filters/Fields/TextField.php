<?php
declare(strict_types = 1);

namespace App\Core\Application\Filters\Fields;

trait TextField
{

    public function valueLike(string $fieldName, string $value)
    {
        $this->builder->andWhere("$fieldName like :value")
                      ->setParameter('value', "%$value%");
    }

    public function valueEquals(string $fieldName, string $value)
    {
        $this->builder->andWhere("$fieldName = :value")
                      ->setParameter('value', $value);
    }

}
