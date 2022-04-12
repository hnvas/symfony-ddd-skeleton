<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Repository;

use App\Core\Domain\Model\Permission;
use App\Core\Domain\Repository\PermissionRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PermissionRepository extends ServiceEntityRepository implements
    PermissionRepositoryInterface
{
    use RepositoryBehaviorTrait, RepositorySearchableTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Permission::class);
    }
}
