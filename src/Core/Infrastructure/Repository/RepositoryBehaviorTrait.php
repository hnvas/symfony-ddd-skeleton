<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Repository;

use App\Core\Domain\Model\Entity;

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

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function flush(): void
    {
        $this->_em->flush();
    }
}
