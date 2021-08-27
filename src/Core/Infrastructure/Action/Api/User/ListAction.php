<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Action\Api\User;

use App\Core\Domain\Repository\UserRepositoryInterface;
use App\Core\Infrastructure\Http\Request\QueryParams;
use App\Core\Infrastructure\Http\Response\ApiResponse;
use App\Core\Infrastructure\Support\Paginator;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ListAction
 * @package App\Core\Infrastructure\Action\Api\User
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 *
 * @Route("/user", name="listUsers", methods={"GET"})
 */
class ListAction
{

    /**
     * @var \App\Core\Infrastructure\Http\Request\QueryParams
     */
    private QueryParams $queryParams;

    /**
     * @var \JMS\Serializer\SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @var \App\Core\Domain\Repository\UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    /**
     * @param \App\Core\Infrastructure\Http\Request\QueryParams $queryParams
     * @param \JMS\Serializer\SerializerInterface $serializer
     * @param \App\Core\Domain\Repository\UserRepositoryInterface $userRepository
     */
    public function __construct(
        QueryParams             $queryParams,
        SerializerInterface     $serializer,
        UserRepositoryInterface $userRepository
    ) {
        $this->userRepository = $userRepository;
        $this->queryParams    = $queryParams;
        $this->serializer     = $serializer;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(): Response
    {
        $criteria = $this->queryParams->criteria();
        $users    = $this->userRepository->search($criteria);
        $pages    = (new Paginator($users, $this->queryParams))->paginate();

        return new ApiResponse($this->serializer->serialize($pages, 'json'));
    }

}
