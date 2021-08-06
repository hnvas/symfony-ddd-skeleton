<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Action\Api\User;

use App\Core\Application\Services\Facades\UserFacade;
use App\Core\Domain\Model\User;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @var \JMS\Serializer\SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * CreateAction constructor.
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \App\Core\Application\Exceptions\InvalidEntityException
     */
    public function __invoke(Request $request): Response
    {
        $content = $request->getContent();

        /** @var User $userData */
        $userData = $this->serializer->deserialize($content, User::class, 'json');
        $user     = $this->userFacade->create($userData);

        return new Response(
            $this->serializer->serialize($user, 'json'),
            Response::HTTP_CREATED,
            ['content-type' => 'json']
        );
    }

}
