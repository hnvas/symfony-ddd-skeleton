<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Support;

use App\Core\Infrastructure\Http\Request\QueryParams;
use Hateoas\Configuration\Route as HateoasRoute;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Hateoas\Representation\PaginatedRepresentation;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

class Paginator
{

    /**
     * @var array
     */
    private array $collection;

    /**
     * @var \App\Core\Infrastructure\Http\Request\QueryParams
     */
    private QueryParams $queryParams;

    /**
     * @param array $collection
     * @param \App\Core\Infrastructure\Http\Request\QueryParams $queryParams
     */
    public function __construct(array $collection, QueryParams $queryParams)
    {
        $this->collection = $collection;
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

        $pager = new Pagerfanta(new ArrayAdapter($this->collection));
        $pager->setMaxPerPage($this->queryParams->limit())
              ->setCurrentPage($this->queryParams->page());

        return $paginatorFactory->createRepresentation($pager, $hateoasRoute);
    }

}
