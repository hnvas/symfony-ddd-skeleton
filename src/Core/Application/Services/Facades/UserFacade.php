<?php
declare(strict_types = 1);

namespace App\Core\Application\Services\Facades;

use App\Core\Application\Filters\UserFilter;
use App\Core\Application\Repository\UserRepository;
use App\Core\Domain\Model\Entity;
use App\Core\Domain\Model\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserFacade extends EntityFacade
{

    /**
     * @var \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $passwordHarsher;

    /**
     * @param \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface $passwordHarsher
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @param \App\Core\Application\Repository\UserRepository $repository
     * @param \Symfony\Component\Validator\Validator\ValidatorInterface $validator
     */
    public function __construct(
        UserPasswordHasherInterface $passwordHarsher,
        EntityManagerInterface      $manager,
        UserRepository              $repository,
        ValidatorInterface          $validator
    ) {
        $this->passwordHarsher = $passwordHarsher;

        parent::__construct($manager, $repository, $validator);
    }

    /**
     * @param \App\Core\Application\Filters\UserFilter $userFilter
     * @param array $params
     *
     * @return array
     */
    public function list(UserFilter $userFilter, array $params): array
    {
        $qb = $this->repository->createQueryBuilder('u')->select('u');

        return $userFilter->apply($qb, $params)->getQuery()->execute();
    }

    /**
     * @param \App\Core\Domain\Model\User $user
     *
     * @return string
     */
    public function hashPassword(User $user): string
    {
        return $this->passwordHarsher->hashPassword(
            $user, $user->getPassword()
        );
    }

    /**
     * @param \App\Core\Domain\Model\Entity $persistentEntity
     * @param \App\Core\Domain\Model\Entity $entity
     */
    protected function patch(Entity $persistentEntity, Entity $entity): void
    {
        $persistentEntity->setName($entity->getName());
        $persistentEntity->setEmail($entity->getEmail());

        if (!empty($entity->getPassword())) {
            $persistentEntity->setPassword($this->hashPassword($entity));
        }
    }

}
