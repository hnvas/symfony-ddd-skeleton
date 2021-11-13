<?php
declare(strict_types = 1);

namespace App\Core\Application\Services\Facades;

use App\Core\Domain\Model\Entity;
use App\Core\Domain\Model\User;
use App\Core\Domain\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserFacade extends EntityFacade
{
    private UserPasswordHasherInterface $passwordHarsher;

    public function __construct(
        UserPasswordHasherInterface $passwordHarsher,
        EntityManagerInterface      $manager,
        UserRepositoryInterface     $repository,
        ValidatorInterface          $validator
    ) {
        $this->passwordHarsher = $passwordHarsher;

        parent::__construct($manager, $repository, $validator);
    }

    /**
     * @param \App\Core\Domain\Model\User $entity
     *
     * @return \App\Core\Domain\Model\Entity
     * @throws \App\Core\Application\Exceptions\InvalidEntityException
     */
    public function create(Entity $entity): Entity
    {
        $entity->setPassword($this->hashPassword($entity));

        return parent::create($entity);
    }

    /**
     * @param \App\Core\Domain\Model\User $user
     *
     * @return string
     */
    private function hashPassword(User $user): string
    {
        return $this->passwordHarsher->hashPassword(
            $user, $user->getPassword()
        );
    }

    /**
     * @param \App\Core\Domain\Model\User $persistentEntity
     * @param \App\Core\Domain\Model\User $entity
     */
    protected function patch(Entity $persistentEntity, Entity $entity): void
    {
        $persistentEntity->setName($entity->getName());
        $persistentEntity->setEmail($entity->getEmail());
        $persistentEntity->setRoles($entity->getRoles());

        if (!empty($entity->getPassword())) {
            $persistentEntity->setPassword($this->hashPassword($entity));
        }
    }

}
