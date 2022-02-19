<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Controller\Api;

use App\Core\Domain\Repository\UserRepositoryInterface as UserRepository;
use App\Core\Infrastructure\Http\Request\QueryParams;
use JMS\Serializer\SerializerInterface as Serializer;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface as Validator;

/**
 * Class UserResource
 * @package App\Core\Infrastructure\Resource
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 *
 * @Route("/user", name="user_resource_")
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
}
