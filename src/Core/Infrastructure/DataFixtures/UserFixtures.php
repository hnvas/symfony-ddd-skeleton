<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\DataFixtures;

use App\Core\Domain\Model\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private string $environment;
    /**
     * @var \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        KernelInterface             $kernel,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->environment    = $kernel->getEnvironment();
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $password = $this->passwordHasher
            ->hashPassword($user, '12345678910');

        $user->setEmail('user@admin.com');
        $user->setName('admin');
        $user->setPassword($password);
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);

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
            $user = new User();
            $password = $this->passwordHasher
                ->hashPassword($user, $userData['password']);

            $user->setEmail($userData['email']);
            $user->setName($userData['name']);
            $user->setPassword($password);

            $manager->persist($user);
        }
    }
}
