<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Support;

use App\Core\Infrastructure\Http\Request\QueryParams;
use Hateoas\Configuration\Route as HateoasRoute;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Hateoas\Representation\PaginatedRepresentation;
use Pagerfanta\Adapter\AdapterInterface;
use Pagerfanta\Pagerfanta;

class PaginatorAssembler
{
    private AdapterInterface $adapter;
    private QueryParams $queryParams;

    public function __construct(AdapterInterface $adapter, QueryParams $params)
    {
        $this->adapter = $adapter;
        $this->queryParams  = $params;
    }

    public function assemble(): PaginatedRepresentation
    {
        $paginatorFactory = new PagerfantaFactory('page', 'limit');
        $hateoasRoute     = new HateoasRoute(
            $this->queryParams->route(),
            $this->queryParams->all()
        );

        $pager = (new Pagerfanta($this->adapter))
            ->setMaxPerPage($this->queryParams->limit())
            ->setCurrentPage($this->queryParams->page());

        return $paginatorFactory->createRepresentation($pager, $hateoasRoute);
    }
}
