<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Repository;

use App\Core\Domain\Repository\Pageable;
use App\Core\Infrastructure\Repository\Filters\FilterFactory;
use App\Core\Infrastructure\Support\PaginatorAdapter;

trait RepositorySearchableTrait
{

    public function search(array $params): Pageable
    {
        $queryBuilder = $this->createQueryBuilder('e');
        $filter       = FilterFactory::create(
            $params,
            $queryBuilder,
            $this->getEntityClassName());

        return new PaginatorAdapter($filter->apply());
    }

}
