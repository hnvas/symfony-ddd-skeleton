<?php
declare(strict_types = 1);

namespace App\Core\Domain\Repository;

interface SearchableRepositoryInterface
{
    public function search(array $params): array;

    public function getEntityClassName(): string;
}
