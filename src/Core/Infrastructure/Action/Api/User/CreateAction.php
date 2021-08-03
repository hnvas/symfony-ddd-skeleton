<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Action\Api\User;

use App\Core\Application\Services\Facades\UserFacade;
use App\Core\Domain\Model\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class CreateAction
 * @package App\Core\Infrastructure\Action\Api\User
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 *
 * @Route("/user", name="createUser", methods={"POST"})
 */
class CreateAction
{

    /**
     * @var \App\Core\Application\Services\Facades\UserFacade
     */
    private UserFacade $userFacade;

    /**
     * @var \Symfony\Component\Serializer\SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * CreateAction constructor.
     *
     * @param \App\Core\Application\Services\Facades\UserFacade $userFacade
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     */
    public function __construct(
        UserFacade          $userFacade,
        SerializerInterface $serializer
    ) {
        $this->userFacade = $userFacade;
        $this->serializer = $serializer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \App\Core\Application\Exceptions\InvalidEntityException
     */
    public function __invoke(Request $request): JsonResponse
    {
        $content  = $request->getContent();

        /** @var User $userData */
        $userData = $this->serializer->deserialize($content, User::class, 'json');

        $hashedPassword = $this->userFacade->hashPassword($userData);
        $userData->setPassword($hashedPassword);

        $user = $this->userFacade->create($userData);

        return new JsonResponse($user, Response::HTTP_CREATED);
    }

}
