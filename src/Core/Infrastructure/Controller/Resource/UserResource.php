<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Controller\Resource;

use App\Core\Domain\Repository\UserRepositoryInterface as UserRepository;
use App\Core\Infrastructure\Http\Request\QueryParams;
use JMS\Serializer\SerializerInterface as Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface as Validator;

/**
 * Class UserResource
 * @package App\Core\Infrastructure\Resource
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 *
 * @Route("/user")
 */
class UserResource extends BaseResource
{

    public function __construct(
        UserRepository $repository,
        Validator      $validator,
        Serializer     $serializer,
        QueryParams    $queryParams
    ) {
        parent::__construct($repository, $validator, $serializer, $queryParams);
    }

    /**
     * @Route("/", name="listUsers", methods={"GET"})
     */
    public function index(): Response
    {
        return parent::index();
    }

    /**
     * @Route("/{id}", name="readUser", methods={"GET"})
     */
    public function read(int $id): Response
    {
        return parent::read($id);
    }

    /**
     * @Route("/", name="createUser", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        return parent::create($request);
    }

    /**
     * @Route("/{id}", name="updateUser", methods={"PUT"})
     */
    public function update(int $id, Request $request): Response
    {
        return parent::update($id, $request);
    }

    /**
     * @Route("/{id}", name="deleteUser", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        return parent::delete($id);
    }
}
