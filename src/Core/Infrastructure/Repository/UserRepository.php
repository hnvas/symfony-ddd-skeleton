<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Repository;

use App\Core\Domain\Model\Entity;
use App\Core\Domain\Model\User;
use App\Core\Domain\Repository\SearchableRepositoryInterface;
use App\Core\Domain\Repository\UserRepositoryInterface;
use App\Core\Infrastructure\Repository\Filters\UserFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

class UserRepository extends ServiceEntityRepository implements
    PasswordUpgraderInterface,
    UserRepositoryInterface,
    SearchableRepositoryInterface
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        ManagerRegistry             $registry,
        UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct($registry, User::class);
        $this->passwordHasher = $passwordHasher;
    }

    public function upgradePassword(
        PasswordAuthenticatedUserInterface $user,
        string                             $newHashedPassword
    ): void {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @param \App\Core\Domain\Model\User $entity
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function add(Entity $entity): void
    {
        $hashedPassword = $this->passwordHasher->hashPassword(
            $entity,
            $entity->getPassword()
        );

        $entity->setPassword($hashedPassword);

        $this->_em->persist($entity);
    }

    /**
     * @param \App\Core\Domain\Model\User $entity
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
     * @return \App\Core\Domain\Model\User|null
     */
    public function findById(int $entityId): ?User
    {
        return $this->find($entityId);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function search(array $params): array
    {
        $qb     = $this->createQueryBuilder('u')->select('u');
        $filter = new UserFilter($params, $qb);

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
