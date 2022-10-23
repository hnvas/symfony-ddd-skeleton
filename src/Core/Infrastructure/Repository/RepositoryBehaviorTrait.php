<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Repository;

use App\Core\Domain\Model\Entity;
use App\Core\Domain\Repository\Pageable;
use App\Core\Infrastructure\Repository\Filters\FilterFactory;
use App\Core\Infrastructure\Support\PaginatorAdapter;

trait RepositoryBehaviorTrait
{
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

    /**
     * @param int $entityId
     *
     * @return \App\Core\Domain\Model\Entity|null
     */
    public function findById(int $entityId): ?Entity
    {
        return $this->find($entityId);
    }

    /**
     * @return string
     */
    public function getEntityClassName(): string
    {
        return $this->getEntityName();
    }

    public function search(array $params): Pageable
    {
        $queryBuilder = $this->createQueryBuilder('e');
        $filter       = FilterFactory::create(
            $params,
            $queryBuilder,
            $this->getEntityClassName());

        return new PaginatorAdapter($filter->apply());
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
