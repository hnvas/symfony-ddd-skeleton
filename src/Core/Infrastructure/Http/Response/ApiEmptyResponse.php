<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Http\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiEmptyResponse
 * @package App\Core\Infrastructure\Http\Response
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class ApiEmptyResponse extends JsonResponse
{

    public function __construct()
    {
        parent::__construct(null, Response::HTTP_NO_CONTENT, [], false);
    }
}
