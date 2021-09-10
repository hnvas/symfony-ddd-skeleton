<?php
declare(strict_types = 1);

namespace App\Core\Application\Services\Facades;

use App\Core\Application\Exceptions\EntityNotFoundException;
use App\Core\Application\Exceptions\InvalidEntityException;
use App\Core\Application\Services\Concerns\Validatable;
use App\Core\Domain\Model\Entity;
use App\Core\Domain\Repository\EntityRepositoryInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
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
     * @var \App\Core\Domain\Repository\EntityRepositoryInterface
     */
    protected EntityRepositoryInterface $repository;

    /**
     * @var \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    protected ValidatorInterface $validator;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @param \App\Core\Domain\Repository\EntityRepositoryInterface $repository
     * @param \Symfony\Component\Validator\Validator\ValidatorInterface $validator
     */
    public function __construct(
        EntityManagerInterface    $manager,
        EntityRepositoryInterface $repository,
        ValidatorInterface        $validator
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

        try {
            $this->repository->add($entity);
            $this->manager->flush();
        } catch (UniqueConstraintViolationException $ex) {
            throw new InvalidEntityException(get_class($entity));
        }

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
        $user = $this->repository->findById($id);

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

        $persistentEntity = $this->repository->findById($id);

        if (is_null($persistentEntity)) {
            throw new EntityNotFoundException($this->repository->getClassName());
        }

        $this->patch($persistentEntity, $entity);

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

        $this->repository->remove($persistentUser);
        $this->manager->flush();
    }

    protected abstract function patch(Entity $persistentEntity, Entity $entity);

}
