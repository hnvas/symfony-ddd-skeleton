<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Repository;

use App\Core\Domain\Model\Entity;
use App\Core\Domain\Model\Module;
use App\Core\Domain\Repository\ModuleRepositoryInterface;
use App\Core\Domain\Repository\SearchableRepositoryInterface;
use App\Core\Infrastructure\Repository\Filters\ModuleFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ModuleRepository extends ServiceEntityRepository implements
    ModuleRepositoryInterface,
    SearchableRepositoryInterface
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Module::class);
    }

    /**
     * @param \App\Core\Domain\Model\Module $entity
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function add(Entity $entity): void
    {
        $this->_em->persist($entity);
    }

    /**
     * @param \App\Core\Domain\Model\Module $entity
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function remove(Entity $entity): void
    {
        $this->_em->remove($entity);
    }

    public function findById(int $entityId): ?Module
    {
        return $this->find($entityId);
    }

    public function search(array $params): array
    {
        $qb     = $this->createQueryBuilder('m')->select('m');
        $filter = new ModuleFilter($params, $qb);

        return $filter->apply()->getQuery()->execute();
    }

    public function getEntityClassName(): string
    {
        return $this->getEntityName();
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function flush(): void
    {
        $this->_em->flush();
    }
}
