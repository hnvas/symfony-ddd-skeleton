<?php
declare(strict_types = 1);

namespace App\Core\Application\Services\Crud;

use App\Core\Application\Exceptions\InvalidDataException;
use App\Core\Application\Exceptions\NotFoundException;
use App\Core\Domain\Model\Entity;
use App\Core\Domain\Repository\EntityRepositoryInterface as EntityRepository;
use App\Core\Domain\Repository\Pageable;
use Doctrine\DBAL\Exception\ConstraintViolationException;
use Symfony\Component\Validator\Validator\ValidatorInterface as Validator;

/**
 * Class CrudFacade
 * @package App\Core\Application\Services\Crud
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
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
     * @throws \App\Core\Application\Exceptions\InvalidDataException
     */
    public function save(Entity $entity): Entity
    {
        $this->validate($this->validator, $entity);

        try {
            $this->repository->add($entity);
            $this->repository->flush();
        } catch (ConstraintViolationException $ex) {
            throw new InvalidDataException($this->entityName);
        }

        return $entity;
    }

    /**
     * @param int $id
     *
     * @return \App\Core\Domain\Model\Entity
     * @throws \App\Core\Application\Exceptions\NotFoundException
     */
    public function read(int $id): Entity
    {
        $entity = $this->repository->findById($id);

        if (is_null($entity)) {
            throw new NotFoundException($this->entityName);
        }

        return $entity;
    }

    /**
     * @param int $id
     *
     * @throws \App\Core\Application\Exceptions\NotFoundException
     */
    public function delete(int $id): void
    {
        $entity = $this->repository->findById($id);

        if (is_null($entity)) {
            throw new NotFoundException($this->entityName);
        }

        $this->repository->remove($entity);
        $this->repository->flush();
    }

    public function search(array $criteria): Pageable
    {
        return $this->repository->search($criteria);
    }
}
