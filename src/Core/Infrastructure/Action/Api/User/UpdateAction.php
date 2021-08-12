<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Action\Api\User;

use App\Core\Application\Services\Facades\UserFacade;
use App\Core\Domain\Model\User;
use App\Core\Infrastructure\Http\Response\ApiResponse;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @var \App\Core\Application\Services\Facades\UserFacade
     */
    private UserFacade $userFacade;

    /**
     * @var \JMS\Serializer\SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * UpdateAction constructor.
     *
     * @param \App\Core\Application\Services\Facades\UserFacade $userFacade
     * @param \JMS\Serializer\SerializerInterface $serializer
     */
    public function __construct(
        UserFacade          $userFacade,
        SerializerInterface $serializer
    ) {
        $this->userFacade = $userFacade;
        $this->serializer = $serializer;
    }

    /**
     * @param int $id
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \App\Core\Application\Exceptions\EntityNotFoundException
     * @throws \App\Core\Application\Exceptions\InvalidEntityException
     */
    public function __invoke(int $id, Request $request): JsonResponse
    {
        $content  = $request->getContent();
        $userData = $this->serializer->deserialize($content, User::class, 'json');

        $user = $this->userFacade->update($id, $userData);

        return new ApiResponse($this->serializer->serialize($user, 'json'));
    }

}
