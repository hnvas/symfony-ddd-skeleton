<?php
declare(strict_types = 1);

namespace App\Core\Domain\Repository;

use App\Core\Domain\Model\User;

interface ModuleRepositoryInterface extends EntityRepositoryInterface
{
    public function findByUser(User $user): array;
}
