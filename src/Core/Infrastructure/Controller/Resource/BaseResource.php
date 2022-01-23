<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Controller\Resource;

use App\Core\Application\Services\Crud\CrudFacade;
use App\Core\Domain\Repository\EntityRepositoryInterface as EntityRepository;
use App\Core\Infrastructure\Http\Request\QueryParams;
use App\Core\Infrastructure\Http\Response\ApiEmptyResponse;
use App\Core\Infrastructure\Http\Response\ApiResponse;
use App\Core\Infrastructure\Support\Paginator;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializerInterface as Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface as Validator;

abstract class BaseResource
{
    private const SERIALIZATION_FORMAT = 'json';

    private CrudFacade  $facade;
    private QueryParams $queryParams;
    private Serializer  $serializer;

    public function __construct(
        EntityRepository $repository,
        Validator        $validator,
        Serializer       $serializer,
        QueryParams      $queryParams
    ) {
        $this->facade      = new CrudFacade($repository, $validator);
        $this->serializer  = $serializer;
        $this->queryParams = $queryParams;
    }

    public function index(): Response
    {
        $criteria = $this->queryParams->criteria();
        $entities = $this->facade->search($criteria);
        $pages    = (new Paginator($entities, $this->queryParams))->paginate();

        return new ApiResponse(
            $this->serializer->serialize($pages, self::SERIALIZATION_FORMAT)
        );
    }

    public function read(int $id): Response
    {
        $entity = $this->facade->read($id);

        return new ApiResponse(
            $this->serializer->serialize($entity, self::SERIALIZATION_FORMAT)
        );
    }

    public function create(Request $request): Response
    {
        $content = $request->getContent();
        $entity = $this->serializer->deserialize(
            $content,
            $this->facade->entityName,
            self::SERIALIZATION_FORMAT
        );

        $this->facade->save($entity);

        return new ApiResponse(
            $this->serializer->serialize($entity, self::SERIALIZATION_FORMAT),
            Response::HTTP_CREATED
        );
    }

    public function update(int $id, Request $request): Response
    {
        $entity = $this->facade->read($id);
        $content = $request->getContent();
        $context = DeserializationContext::create();
        $context->setAttribute('target', $entity);
        $patchedEntity = $this->serializer->deserialize(
            $content,
            $this->facade->entityName,
            self::SERIALIZATION_FORMAT,
            $context
        );

        $this->facade->save($patchedEntity);

        return new ApiResponse(
            $this->serializer->serialize($entity, self::SERIALIZATION_FORMAT)
        );
    }

    public function delete(int $id): Response
    {
        $this->facade->delete($id);

        return new ApiEmptyResponse();
    }

}
