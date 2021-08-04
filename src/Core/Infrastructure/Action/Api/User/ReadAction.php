<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Action\Api\User;

use App\Core\Application\Services\Facades\UserFacade;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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
     * @var \App\Core\Application\Services\Facades\UserFacade
     */
    private UserFacade $userFacade;

    /**
     * @var \JMS\Serializer\SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param \App\Core\Application\Services\Facades\UserFacade $userFacade
     * @param \JMS\Serializer\SerializerInterface $serializer
     */
    public function __construct(UserFacade $userFacade, SerializerInterface $serializer)
    {
        $this->userFacade = $userFacade;
        $this->serializer = $serializer;
    }

    /**
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \App\Core\Application\Exceptions\EntityNotFoundException
     */
    public function __invoke(int $id): Response
    {
        $user = $this->userFacade->read($id);

        return new Response(
            $this->serializer->serialize($user, 'json'),
            Response::HTTP_OK,
            ['content-type' => 'json']
        );
    }
}
