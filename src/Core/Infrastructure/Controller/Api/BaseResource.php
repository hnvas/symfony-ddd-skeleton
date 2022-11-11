<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Controller\Api;

use App\Core\Application\Services\CRUD\CrudService;
use App\Core\Domain\Repository\EntityRepositoryInterface;
use App\Core\Domain\Repository\EntityRepositoryInterface as EntityRepository;
use App\Core\Infrastructure\Http\Request\QueryParams;
use App\Core\Infrastructure\Http\Response\ApiEmptyResponse;
use App\Core\Infrastructure\Http\Response\ApiResponse;
use App\Core\Infrastructure\Support\PaginatorAssembler;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializerInterface as Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface as Validator;

/**
 * Class BaseResource
 * @package App\Core\Infrastructure\Controller\Api
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
abstract class BaseResource extends AbstractController
{
    private const SERIALIZATION_FORMAT = 'json';

    private EntityRepositoryInterface $repository;
    private CrudService               $facade;
    private QueryParams               $queryParams;
    private Serializer                $serializer;

    public function __construct(
        EntityRepository $repository,
        Validator        $validator,
        Serializer       $serializer,
        QueryParams      $queryParams
    ) {
        $this->repository  = $repository;
        $this->facade      = new CrudService($repository, $validator);
        $this->serializer  = $serializer;
        $this->queryParams = $queryParams;
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $this->denyAccessUnlessGranted('index', $request);

        $criteria = $this->queryParams->criteria();
        $entities = $this->facade->search($criteria);
        $pages    = (new PaginatorAssembler($entities, $this->queryParams))->assemble();

        return new ApiResponse(
            $this->serializer->serialize($pages, self::SERIALIZATION_FORMAT)
        );
    }

    /**
     * @Route("/{id}", name="read", methods={"GET"})
     */
    public function read(int $id, Request $request): Response
    {
        $this->denyAccessUnlessGranted('read', $request);

        $entity = $this->facade->read($id);

        return new ApiResponse(
            $this->serializer->serialize($entity, self::SERIALIZATION_FORMAT)
        );
    }

    /**
     * @Route("/", name="create", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $this->denyAccessUnlessGranted('create', $request);

        $content = $request->getContent();
        $entity  = $this->serializer->deserialize(
            $content,
            $this->repository->getEntityClassName(),
            self::SERIALIZATION_FORMAT
        );

        $this->facade->save($entity);

        return new ApiResponse(
            $this->serializer->serialize($entity, self::SERIALIZATION_FORMAT),
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route("/{id}", name="update", methods={"PUT"})
     */
    public function update(int $id, Request $request): Response
    {
        $this->denyAccessUnlessGranted('update', $request);

        $entity  = $this->facade->read($id);
        $content = $request->getContent();
        $context = DeserializationContext::create();
        $context->setAttribute('target', $entity);
        $patchedEntity = $this->serializer->deserialize(
            $content,
            $this->repository->getEntityClassName(),
            self::SERIALIZATION_FORMAT,
            $context
        );

        $this->facade->save($patchedEntity);

        return new ApiResponse(
            $this->serializer->serialize($entity, self::SERIALIZATION_FORMAT)
        );
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(int $id, Request $request): Response
    {
        $this->denyAccessUnlessGranted('delete', $request);

        $this->facade->delete($id);

        return new ApiEmptyResponse();
    }

}
