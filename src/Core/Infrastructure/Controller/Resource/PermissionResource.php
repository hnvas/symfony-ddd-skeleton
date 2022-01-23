<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Controller\Resource;

use App\Core\Domain\Repository\PermissionRepositoryInterface as PermissionRepository;
use App\Core\Infrastructure\Http\Request\QueryParams;
use JMS\Serializer\SerializerInterface as Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface as Validator;

/**
 * Class PermissionResource
 * @package App\Core\Infrastructure\Resource
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 *
 * @Route("/permission")
 */
class PermissionResource extends BaseResource
{

    public function __construct(
        PermissionRepository $repository,
        Validator        $validator,
        Serializer       $serializer,
        QueryParams      $queryParams
    ) {
        parent::__construct($repository, $validator, $serializer, $queryParams);
    }

    /**
     * @Route("/", name="listPermissions", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        return parent::index($request);
    }

    /**
     * @Route("/{id}", name="readPermission", methods={"GET"})
     */
    public function read(int $id, Request $request): Response
    {
        return parent::read($id, $request);
    }

    /**
     * @Route("/", name="createPermission", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        return parent::create($request);
    }

    /**
     * @Route("/{id}", name="updatePermission", methods={"PUT"})
     */
    public function update(int $id, Request $request): Response
    {
        return parent::update($id, $request);
    }

    /**
     * @Route("/{id}", name="deletePermission", methods={"DELETE"})
     */
    public function delete(int $id, Request $request): Response
    {
        return parent::delete($id, $request);
    }
}
