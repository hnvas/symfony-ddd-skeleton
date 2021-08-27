<?php
declare(strict_types = 1);

namespace App\Core\Domain\Repository;

interface UserRepositoryInterface
{

    /**
     * @param array $params
     *
     * @return array
     */
    public function search(array $params): array;

}
