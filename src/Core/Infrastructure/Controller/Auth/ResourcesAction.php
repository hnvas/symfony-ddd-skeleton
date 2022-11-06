<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Controller\Auth;

use App\Core\Application\Query\UserPermission\GetUserPermissions;
use App\Core\Infrastructure\Http\Response\ApiResponse;
use App\Core\Infrastructure\Security\AuthUser;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ResourcesAction
 * @package App\Core\Infrastructure\Controller\Auth
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
#[Route('/resources', name: 'user_resources_action', methods: ['GET'])]
class ResourcesAction extends AbstractController
{

    public function __construct(
        private readonly GetUserPermissions  $userPermissions,
        private readonly SerializerInterface $serializer
    ){
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\DBAL\Exception
     */
    public function __invoke(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        /** @var AuthUser $user */
        $user = $this->getUser();
        $resources = $this->userPermissions->execute($user->model());

        return new ApiResponse($this->serializer->serialize($resources, 'json'));
    }
}
