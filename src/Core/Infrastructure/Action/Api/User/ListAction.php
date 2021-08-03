<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Action\Api\User;

use App\Core\Application\Filters\UserFilter;
use App\Core\Application\Services\Facades\UserFacade;
use App\Core\Infrastructure\Request\QueryParams;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * ListAction constructor.
     *
     * @param \App\Core\Application\Services\Facades\UserFacade $userFacade
     * @param \App\Core\Infrastructure\Request\QueryParams $queryParams
     */
    public function __construct(UserFacade $userFacade, QueryParams $queryParams)
    {
        $this->userFacade  = $userFacade;
        $this->queryParams = $queryParams;
    }

    public function __invoke(): JsonResponse
    {
        $users  = $this->userFacade->list(
            new UserFilter(),
            $this->queryParams->criteria()
        );

        return new JsonResponse($users);
    }

}
