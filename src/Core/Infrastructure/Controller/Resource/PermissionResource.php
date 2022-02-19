<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Controller\Resource;

use App\Core\Domain\Repository\PermissionRepositoryInterface as PermissionRepository;
use App\Core\Infrastructure\Http\Request\QueryParams;
use JMS\Serializer\SerializerInterface as Serializer;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface as Validator;

/**
 * Class PermissionResource
 * @package App\Core\Infrastructure\Resource
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 *
 * @Route("/permission", name="permission_resource_")
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
}
