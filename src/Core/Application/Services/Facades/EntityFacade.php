<?php
declare(strict_types = 1);

namespace App\Core\Application\Services\Facades;

use App\Core\Application\Exceptions\EntityNotFoundException;
use App\Core\Application\Services\Concerns\Validatable;
use App\Core\Domain\Model\Entity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class EntityFacade
{

    use Validatable;

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected EntityManagerInterface $manager;

    /**
     * @var \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface
     */
    protected ServiceEntityRepositoryInterface $repository;

    /**
     * @var \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    protected ValidatorInterface $validator;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @param \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface $repository
     * @param \Symfony\Component\Validator\Validator\ValidatorInterface $validator
     */
    public function __construct(
        EntityManagerInterface           $manager,
        ServiceEntityRepositoryInterface $repository,
        ValidatorInterface               $validator
    ) {
        $this->manager    = $manager;
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    /**
     * @param \App\Core\Domain\Model\Entity $entity
     *
     * @return \App\Core\Domain\Model\Entity
     *
     * @throws \App\Core\Application\Exceptions\InvalidEntityException
     */
    public function create(Entity $entity): Entity
    {
        $this->validate($this->validator, $entity);

        $this->manager->persist($entity);
        $this->manager->flush();

        return $entity;
    }

    /**
     * @param int $id
     *
     * @return \App\Core\Domain\Model\Entity
     * @throws \App\Core\Application\Exceptions\EntityNotFoundException
     */
    public function read(int $id): Entity
    {
        $user = $this->repository->find($id);

        if (is_null($user)) {
            throw new EntityNotFoundException($this->repository->getClassName());
        }

        return $user;
    }

    /**
     * @param int $id
     * @param \App\Core\Domain\Model\Entity $entity
     *
     * @return \App\Core\Domain\Model\Entity
     * @throws \App\Core\Application\Exceptions\EntityNotFoundException
     * @throws \App\Core\Application\Exceptions\InvalidEntityException
     */
    public function update(int $id, Entity $entity): Entity
    {
        $this->validate($this->validator, $entity);

        $persistentEntity = $this->repository->find($id);

        if (is_null($persistentEntity)) {
            throw new EntityNotFoundException($this->repository->getClassName());
        }

        $this->patch($persistentEntity, $entity);

        $this->manager->persist($persistentEntity);
        $this->manager->flush();

        return $persistentEntity;
    }

    /**
     * @param int $id
     *
     * @throws \App\Core\Application\Exceptions\EntityNotFoundException
     */
    public function delete(int $id): void
    {
        $persistentUser = $this->repository->find($id);

        if (is_null($persistentUser)) {
            throw new EntityNotFoundException($this->repository->getClassName());
        }

        $this->manager->remove($persistentUser);
        $this->manager->flush();
    }

    protected abstract function patch(Entity $persistentEntity, Entity $entity);

}
