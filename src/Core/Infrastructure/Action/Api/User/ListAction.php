<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Action\Api\User;

use App\Core\Application\Filters\UserFilter;
use App\Core\Application\Services\Facades\UserFacade;
use App\Core\Infrastructure\Request\QueryParams;
use Doctrine\ORM\QueryBuilder;
use Hateoas\Configuration\Route as HateoasRoute;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Hateoas\Representation\PaginatedRepresentation;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ListAction
 * @package App\Core\Infrastructure\Action\Api\User
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 *
 * @Route("/user", name="listUsers", methods={"GET"})
 */
class ListAction
{

    /**
     * @var \App\Core\Application\Services\Facades\UserFacade
     */
    private UserFacade $userFacade;

    /**
     * @var \App\Core\Infrastructure\Request\QueryParams
     */
    private QueryParams $queryParams;

    /**
     * @var \JMS\Serializer\SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param \App\Core\Application\Services\Facades\UserFacade $userFacade
     * @param \App\Core\Infrastructure\Request\QueryParams $queryParams
     * @param \JMS\Serializer\SerializerInterface $serializer
     */
    public function __construct(
        UserFacade          $userFacade,
        QueryParams         $queryParams,
        SerializerInterface $serializer
    ) {
        $this->userFacade  = $userFacade;
        $this->queryParams = $queryParams;
        $this->serializer  = $serializer;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(): Response
    {
        $users = $this->userFacade->list(
            new UserFilter(),
            $this->queryParams->criteria()
        );

        $pagination = $this->paginate($users);

        return new Response(
            $this->serializer->serialize($pagination, 'json'),
            Response::HTTP_OK,
            ['content-type' => 'json']
        );
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder $builder
     *
     * @return \Hateoas\Representation\PaginatedRepresentation
     */
    protected function paginate(QueryBuilder $builder): PaginatedRepresentation
    {
        $pager = new Pagerfanta(new QueryAdapter($builder));
        $pager->setMaxPerPage($this->queryParams->limit())
              ->setCurrentPage($this->queryParams->page());

        return (new PagerfantaFactory('page', 'limit'))->createRepresentation(
            $pager,
            new HateoasRoute($this->queryParams->route(), $this->queryParams->all())
        );
    }

}
