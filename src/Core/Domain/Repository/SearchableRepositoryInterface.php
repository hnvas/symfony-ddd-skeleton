<?php
declare(strict_types = 1);

namespace App\Core\Domain\Repository;

interface SearchableRepositoryInterface extends EntityRepositoryInterface
{
    public function search(array $params): array;
}
