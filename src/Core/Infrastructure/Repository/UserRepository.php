<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Repository;

use App\Core\Domain\Model\Entity;
use App\Core\Domain\Model\User;
use App\Core\Domain\Repository\SearchableRepositoryInterface;
use App\Core\Domain\Repository\UserRepositoryInterface;
use App\Core\Infrastructure\Security\AuthUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class UserRepository
 * @package App\Core\Infrastructure\Repository
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class UserRepository extends ServiceEntityRepository implements
    UserRepositoryInterface,
    UserLoaderInterface
{
    use RepositoryBehaviorTrait;

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        ManagerRegistry             $registry,
        UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct($registry, User::class);
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @inheritDoc
     * @param \App\Core\Domain\Model\User $entity
     */
    public function add(Entity $entity): void
    {
        $hashedPassword = $this->passwordHasher->hashPassword(
            new AuthUser($entity),
            $entity->password()
        );

        $entity->changePassword($hashedPassword);

        $this->_em->persist($entity);
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
