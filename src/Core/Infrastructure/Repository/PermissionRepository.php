<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Repository;

use App\Core\Domain\Model\Entity;
use App\Core\Domain\Model\Permission;
use App\Core\Domain\Repository\PermissionRepositoryInterface;
use App\Core\Infrastructure\Repository\Filters\PermissionFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PermissionRepository extends ServiceEntityRepository implements
    PermissionRepositoryInterface
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Permission::class);
    }

    /**
     * @param \App\Core\Domain\Model\Permission $entity
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function add(Entity $entity): void
    {
        $this->_em->persist($entity);
    }

    /**
     * @param \App\Core\Domain\Model\Permission $entity
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function remove(Entity $entity): void
    {
        $this->_em->remove($entity);
    }

    public function findById(int $entityId): ?Permission
    {
        return $this->find($entityId);
    }

    public function search(array $params): array
    {
        $qb     = $this->createQueryBuilder('u')->select('u');
        $filter = new PermissionFilter($params, $qb);

        return $filter->apply()->getQuery()->execute();
    }

    public function getEntityClassName(): string
    {
        return $this->getEntityName();
    }
}
