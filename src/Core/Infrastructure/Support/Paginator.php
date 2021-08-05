<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Support;

use App\Core\Infrastructure\Request\QueryParams;
use Doctrine\ORM\QueryBuilder;
use Hateoas\Configuration\Route as HateoasRoute;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Hateoas\Representation\PaginatedRepresentation;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

class Paginator
{

    /**
     * @var \Doctrine\ORM\QueryBuilder
     */
    private QueryBuilder $queryBuilder;

    /**
     * @var \App\Core\Infrastructure\Request\QueryParams
     */
    private QueryParams $queryParams;

    /**
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder
     * @param \App\Core\Infrastructure\Request\QueryParams $queryParams
     */
    public function __construct(QueryBuilder $queryBuilder, QueryParams $queryParams)
    {
        $this->queryBuilder = $queryBuilder;
        $this->queryParams  = $queryParams;
    }

    /**
     * @return \Hateoas\Representation\PaginatedRepresentation
     */
    public function paginate(): PaginatedRepresentation
    {
        $paginatorFactory = new PagerfantaFactory('page', 'limit');
        $hateoasRoute     = new HateoasRoute(
            $this->queryParams->route(),
            $this->queryParams->all()
        );

        $pager = new Pagerfanta(new QueryAdapter($this->queryBuilder));
        $pager->setMaxPerPage($this->queryParams->limit())
              ->setCurrentPage($this->queryParams->page());

        return $paginatorFactory->createRepresentation($pager, $hateoasRoute);
    }

}
