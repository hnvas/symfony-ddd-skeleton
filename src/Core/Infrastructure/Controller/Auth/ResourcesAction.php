<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Controller\Auth;

use App\Core\Domain\Model\User;
use App\Core\Domain\Repository\ModuleRepositoryInterface;
use App\Core\Infrastructure\Http\Response\ApiResponse;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ResourcesAction
 * @package App\Core\Infrastructure\Controller\Auth
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 *
 * @Route("/resources", name="user_resources_action", methods={"GET"})
 */
class ResourcesAction extends AbstractController
{

    private ModuleRepositoryInterface $moduleRepository;

    private SerializerInterface $serializer;

    public function __construct(
        ModuleRepositoryInterface $moduleRepository,
        SerializerInterface $serializer
    ){
        $this->moduleRepository = $moduleRepository;
        $this->serializer = $serializer;
    }

    public function __invoke(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        /** @var User $user */
        $user = $this->getUser();
        $params = ['roles' => $user->roles()];
        $resources = $this->moduleRepository->search($params);

        return new ApiResponse($this->serializer->serialize($resources, 'json'));
    }
}
