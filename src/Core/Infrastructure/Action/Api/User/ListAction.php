<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Action\Api\User;

use App\Core\Application\Services\Facades\UserFacade;
use App\Core\Infrastructure\Http\Request\QueryParams;
use App\Core\Infrastructure\Support\Paginator;
use JMS\Serializer\SerializerInterface;
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
     * @var \App\Core\Infrastructure\Http\Request\QueryParams
     */
    private QueryParams $queryParams;

    /**
     * @var \JMS\Serializer\SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param \App\Core\Application\Services\Facades\UserFacade $userFacade
     * @param \App\Core\Infrastructure\Http\Request\QueryParams $queryParams
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
        $criteria  = $this->queryParams->criteria();
        $result    = $this->userFacade->search($criteria);
        $paginator = new Paginator($result, $this->queryParams);

        return new Response(
            $this->serializer->serialize($paginator->paginate(), 'json'),
            Response::HTTP_OK,
            ['content-type' => 'json']
        );
    }

}
