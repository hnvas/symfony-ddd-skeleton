<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Action\Api\User;

use App\Core\Application\Exceptions\EntityNotFoundException;
use App\Core\Application\Services\UserService;
use App\Core\Domain\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class UpdateAction
 * @package App\Core\Infrastructure\Action\Api\User
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 *
 * @Route("/user/{id}", name="updateUser", methods={"PATCH", "PUT"})
 */
class UpdateAction
{

    /**
     * @var \App\Core\Application\Services\UserService
     */
    private UserService $userService;

    /**
     * @var \Symfony\Component\Serializer\SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * UpdateAction constructor.
     *
     * @param \App\Core\Application\Services\UserService $userService
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     */
    public function __construct(
        UserService $userService,
        SerializerInterface $serializer
    ) {
        $this->userService = $userService;
        $this->serializer  = $serializer;
    }

    public function __invoke(int $id, Request $request): JsonResponse
    {
        $content  = $request->getContent();
        $userData = $this->serializer->deserialize($content, User::class, 'json');

        try {
            $user = $this->userService->update($id, $userData);
        } catch (EntityNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return new JsonResponse($user);
    }

}
