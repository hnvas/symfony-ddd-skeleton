<?php
declare(strict_types = 1);

namespace App\Core\Domain\Repository;

use App\Core\Domain\Model\Entity;

interface EntityRepositoryInterface
{

    public function add(Entity $entity): void;

    public function remove(Entity $entity): void;

    public function findById(int $entityId): ?Entity;

    public function getEntityClassName(): string;

    public function flush(): void;

    public function search(array $params): Pageable;
}
