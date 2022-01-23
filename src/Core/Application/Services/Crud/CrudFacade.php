<?php
declare(strict_types = 1);

namespace App\Core\Application\Services\Crud;

use App\Core\Application\Exceptions\EntityNotFoundException;
use App\Core\Application\Exceptions\InvalidEntityException;
use App\Core\Domain\Model\Entity;
use App\Core\Domain\Repository\EntityRepositoryInterface as EntityRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Validator\Validator\ValidatorInterface as Validator;

final class CrudFacade
{
    use Validatable;

    public string            $entityName;
    private EntityRepository $repository;
    private Validator        $validator;

    public function __construct(
        EntityRepository $repository,
        Validator        $validator
    ) {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->entityName = $repository->getEntityClassName();
    }

    /**
     * @param \App\Core\Domain\Model\Entity $entity
     *
     * @return \App\Core\Domain\Model\Entity
     * @throws \App\Core\Application\Exceptions\InvalidEntityException
     */
    public function save(Entity $entity): Entity
    {
        $this->validate($this->validator, $entity);

        try {
            $this->repository->add($entity);
            $this->repository->flush();
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
        $entity = $this->repository->findById($id);

        if (is_null($entity)) {
            $entity = $this->repository->getEntityClassName();
            throw new EntityNotFoundException($entity);
        }

        return $entity;
    }

    /**
     * @param int $id
     *
     * @throws \App\Core\Application\Exceptions\EntityNotFoundException
     */
    public function delete(int $id): void
    {
        $entity = $this->repository->findById($id);

        if (is_null($entity)) {
            $entity = $this->repository->getEntityClassName();
            throw new EntityNotFoundException($entity);
        }

        $this->repository->remove($entity);
        $this->repository->flush();
    }

    public function search(array $criteria): array
    {
        return $this->repository->search($criteria);
    }
}
