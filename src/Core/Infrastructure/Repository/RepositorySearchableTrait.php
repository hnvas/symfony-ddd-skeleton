<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Repository;

use App\Core\Infrastructure\Repository\Filters\FilterFactory;

trait RepositorySearchableTrait
{

    public function search(array $params): array
    {
        $queryBuilder = $this->createQueryBuilder('e');
        $filter       = FilterFactory::create(
            $params,
            $queryBuilder,
            $this->getEntityClassName());

        return $filter->apply()->getQuery()->execute();
    }

}
