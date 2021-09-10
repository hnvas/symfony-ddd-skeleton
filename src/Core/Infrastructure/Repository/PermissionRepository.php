<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Repository;

use App\Core\Domain\Model\Permission;
use App\Core\Domain\Repository\PermissionRepositoryInterface;
use App\Core\Infrastructure\Repository\Filters\PermissionFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Permission|null find($id, $lockMode = null, $lockVersion = null)
 * @method Permission|null findOneBy(array $criteria, array $orderBy = null)
 * @method Permission[]    findAll()
 * @method Permission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PermissionRepository extends ServiceEntityRepository implements
    PermissionRepositoryInterface
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Permission::class);
    }

    /**
     * @param \App\Core\Domain\Model\Permission $permission
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function add(Permission $permission): void
    {
        $this->_em->persist($permission);
    }

    /**
     * @param \App\Core\Domain\Model\Permission $permission
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function remove(Permission $permission): void
    {
        $this->_em->remove($permission);
    }

    public function findById(int $permissionId): ?Permission
    {
        return $this->find($permissionId);
    }

    public function search(array $params): array
    {
        $qb     = $this->createQueryBuilder('u')->select('u');
        $filter = new PermissionFilter($params, $qb);

        return $filter->apply()->getQuery()->execute();
    }
}
