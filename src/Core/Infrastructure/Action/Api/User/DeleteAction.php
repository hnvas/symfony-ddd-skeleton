<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Action\Api\User;

use App\Core\Application\Services\UserService;
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
     * @var \App\Core\Application\Services\UserService
     */
    private UserService $userService;

    /**
     * DeleteAction constructor.
     *
     * @param \App\Core\Application\Services\UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \App\Core\Application\Exceptions\EntityNotFoundException
     */
    public function __invoke(int $id): JsonResponse
    {
        $this->userService->delete($id);

        return new JsonResponse();
    }

}
