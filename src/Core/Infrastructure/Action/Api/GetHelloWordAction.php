<?php

namespace App\Core\Infrastructure\Action\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ApiHelloWordAction
 * @package App\Core\Infrastructure\Action\Api
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 *
 * @Route("/api/hello", name="hello", methods={"GET"})
 */
class GetHelloWordAction
{

    public function __invoke(): JsonResponse
    {
        return new JsonResponse(['message' => 'Hello']);
    }

}
