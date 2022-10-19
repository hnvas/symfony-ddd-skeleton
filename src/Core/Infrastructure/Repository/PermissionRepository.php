<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Repository;

use App\Core\Domain\Model\Permission;
use App\Core\Domain\Repository\PermissionRepositoryInterface;
use App\Core\Domain\Repository\SearchableRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class PermissionRepository
 * @package App\Core\Infrastructure\Repository
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class PermissionRepository extends ServiceEntityRepository implements
    PermissionRepositoryInterface,
    SearchableRepositoryInterface
{
    use RepositoryBehaviorTrait, RepositorySearchableTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Permission::class);
    }
}
