<?php
declare(strict_types = 1);

namespace App\Core\Application\Services;

use App\Core\Application\Exceptions\EntityNotFoundException;
use App\Core\Application\Functions\Traits\Validatable;
use App\Core\Domain\Entity\User;
use App\Core\Infrastructure\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{
    use Validatable;

    /**
     * @var \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $passwordHarsher;

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    /**
     * @var \App\Core\Infrastructure\Repository\UserRepository
     */
    private UserRepository $repository;

    /**
     * @var \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * @param \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface $passwordHarsher
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @param \App\Core\Infrastructure\Repository\UserRepository $repository
     * @param \Symfony\Component\Validator\Validator\ValidatorInterface $validator
     */
    public function __construct(
        UserPasswordHasherInterface $passwordHarsher, EntityManagerInterface $manager,
        UserRepository              $repository, ValidatorInterface $validator
    ) {
        $this->passwordHarsher = $passwordHarsher;
        $this->manager         = $manager;
        $this->repository      = $repository;
        $this->validator       = $validator;
    }

    /**
     * @param \App\Core\Domain\Entity\User $user
     *
     * @return \App\Core\Domain\Entity\User
     *
     * @throws \App\Core\Application\Exceptions\InvalidEntityException
     */
    public function create(User $user): User
    {
        $this->validate($this->validator, $user);

        $user->setPassword($this->hashPassword($user));

        $this->manager->persist($user);
        $this->manager->flush();

        return $user;
    }

    /**
     * @param int $id
     *
     * @return \App\Core\Domain\Entity\User
     * @throws \App\Core\Application\Exceptions\EntityNotFoundException
     */
    public function read(int $id): User
    {
        $user = $this->repository->find($id);

        if (is_null($user)) {
            throw new EntityNotFoundException(User::class);
        }

        return $user;
    }

    /**
     * @param int $id
     * @param \App\Core\Domain\Entity\User $user
     *
     * @return \App\Core\Domain\Entity\User
     * @throws \App\Core\Application\Exceptions\EntityNotFoundException
     * @throws \App\Core\Application\Exceptions\InvalidEntityException
     */
    public function update(int $id, User $user): User
    {
        $this->validate($this->validator, $user);

        $persistentUser = $this->repository->find($id);

        if (is_null($persistentUser)) {
            throw new EntityNotFoundException(User::class);
        }

        $persistentUser->setName($user->getName());
        $persistentUser->setEmail($user->getEmail());

        if (!empty($user->getPassword())) {
            $persistentUser->setPassword($this->hashPassword($user));
        }

        $this->manager->persist($persistentUser);
        $this->manager->flush();

        return $persistentUser;
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
            throw new EntityNotFoundException(User::class);
        }

        $this->manager->remove($persistentUser);
        $this->manager->flush();
    }

    /**
     * @return array
     */
    public function list(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @param \App\Core\Domain\Entity\User $user
     *
     * @return string
     */
    private function hashPassword(User $user): string
    {
        return $this->passwordHarsher->hashPassword(
            $user, $user->getPassword()
        );
    }

}
