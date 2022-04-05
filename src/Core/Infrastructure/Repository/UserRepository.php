<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Repository;

use App\Core\Domain\Model\Entity;
use App\Core\Domain\Model\User;
use App\Core\Domain\Repository\SearchableRepositoryInterface;
use App\Core\Domain\Repository\UserRepositoryInterface;
use App\Core\Infrastructure\Repository\Filters\UserFilter;
use App\Core\Infrastructure\Security\AuthUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRepository extends ServiceEntityRepository implements
    UserRepositoryInterface,
    SearchableRepositoryInterface,
    UserLoaderInterface
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        ManagerRegistry             $registry,
        UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct($registry, User::class);
        $this->passwordHasher = $passwordHasher;
    }

    public function add(Entity $entity): void
    {
        $hashedPassword = $this->passwordHasher->hashPassword(
            new AuthUser($entity),
            $entity->getPassword()
        );

        $entity->setPassword($hashedPassword);

        $this->_em->persist($entity);
    }

    public function remove(Entity $entity): void
    {
        $this->_em->remove($entity);
    }

    public function findById(int $entityId): ?User
    {
        return $this->find($entityId);
    }

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

    public function flush(): void
    {
        $this->_em->flush();
    }

    public function loadUserByUsername(string $username): ?AuthUser
    {
        return $this->loadUserByIdentifier($username);
    }

    public function loadUserByIdentifier(string $identifier): ?AuthUser
    {
        $user = $this->findOneBy(['email' => $identifier]);

        return !$user ? null : new AuthUser($user);
    }
}
