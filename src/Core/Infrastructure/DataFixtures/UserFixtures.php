<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\DataFixtures;

use App\Core\Domain\Model\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private string                      $environment;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        ContainerBagInterface       $containerBag,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->environment    = $containerBag->get('kernel.environment');
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User(
            'user@admin.com',
            'admin',
            '12345678910',
            ['ROLE_USER', 'ROLE_ADMIN'],
            true,
            true
        );

        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $user->getPassword())
        );

        $manager->persist($user);

        if ('test' === $this->environment) {
            $this->loadMany($manager);
        }

        $manager->flush();
    }

    private function loadMany(ObjectManager $manager)
    {
        $users = [
            [
                'email'    => 'user@test1.com',
                'name'     => 'test1',
                'password' => 'test1pass',
                'roles'    => ['ROLE_USER']
            ],
            [
                'email'    => 'user@test2.com',
                'name'     => 'test2',
                'password' => 'test2pass',
                'roles'    => ['ROLE_USER']
            ]
        ];

        foreach ($users as $userData) {
            $user = new User(
                $userData['email'],
                $userData['name'],
                $userData['password'],
                $userData['roles'],
                true,
                true
            );

            $user->setPassword(
                $this->passwordHasher->hashPassword($user, $user->getPassword())
            );

            $manager->persist($user);
        }
    }
}
