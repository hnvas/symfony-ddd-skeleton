<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Action\Api\Permission;

use App\Core\Domain\Repository\PermissionRepositoryInterface;
use App\Core\Infrastructure\Http\Request\QueryParams;
use App\Core\Infrastructure\Http\Response\ApiResponse;
use App\Core\Infrastructure\Support\Paginator;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexAction
 * @package App\Core\Infrastructure\Action\Api\Permission
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 *
 * @Route("/permission", name="permissionIndex", methods={"GET"})
 */
class IndexAction
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
     * @var \App\Core\Domain\Repository\PermissionRepositoryInterface
     */
    private PermissionRepositoryInterface $permissionRepository;

    /**
     * @param \App\Core\Infrastructure\Http\Request\QueryParams $queryParams
     * @param \JMS\Serializer\SerializerInterface $serializer
     * @param \App\Core\Domain\Repository\PermissionRepositoryInterface $permissionRepository
     */
    public function __construct(
        QueryParams                   $queryParams,
        SerializerInterface           $serializer,
        PermissionRepositoryInterface $permissionRepository
    ) {
        $this->permissionRepository = $permissionRepository;
        $this->queryParams          = $queryParams;
        $this->serializer           = $serializer;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(): Response
    {
        $criteria = $this->queryParams->criteria();
        $users    = $this->permissionRepository->search($criteria);
        $pages    = (new Paginator($users, $this->queryParams))->paginate();

        return new ApiResponse($this->serializer->serialize($pages, 'json'));
    }

}
