<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Http\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponse extends JsonResponse
{

    public function __construct($data = null, int $status = 200, array $headers = [], bool $json = true)
    {
        parent::__construct($data, $status,  $headers, $json);
    }
}
