<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Action\Api\User;

use App\Core\Application\Services\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ReadAction
 * @package App\Core\Infrastructure\Action\Api\User
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 *
 * @Route("/user/{id}", name="readUser", methods={"GET"})
 */
class ReadAction
{

    /**
     * @var \App\Core\Application\Services\UserService
     */
    private UserService $userService;

    /**
     * ReadAction constructor.
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
        $user = $this->userService->read($id);

        return new JsonResponse($user);
    }
}
