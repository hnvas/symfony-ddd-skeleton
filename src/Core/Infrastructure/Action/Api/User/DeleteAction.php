<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Action\Api\User;

use App\Core\Application\Services\Facades\UserFacade;
use App\Core\Infrastructure\Http\Response\ApiResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DeleteAction
 * @package App\Core\Infrastructure\Action\Api\User
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 *
 * @Route("/user/{id}", name="deleteUser", methods={"DELETE"})
 */
class DeleteAction
{

    /**
     * @var \App\Core\Application\Services\Facades\UserFacade
     */
    private UserFacade $userFacade;

    /**
     * DeleteAction constructor.
     *
     * @param \App\Core\Application\Services\Facades\UserFacade $userFacade
     */
    public function __construct(UserFacade $userFacade)
    {
        $this->userFacade = $userFacade;
    }

    /**
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \App\Core\Application\Exceptions\EntityNotFoundException
     */
    public function __invoke(int $id): JsonResponse
    {
        $this->userFacade->delete($id);

        return new ApiResponse();
    }

}
