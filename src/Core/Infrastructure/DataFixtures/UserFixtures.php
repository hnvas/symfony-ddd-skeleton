<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\DataFixtures;

use App\Core\Application\Services\Facades\UserFacade;
use App\Core\Domain\Model\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\KernelInterface;

class UserFixtures extends Fixture
{

    /**
     * @var \App\Core\Application\Services\Facades\UserFacade
     */
    private UserFacade $facade;

    /**
     * @var string
     */
    private string $environment;

    /**
     * @param \App\Core\Application\Services\Facades\UserFacade $facade
     * @param \Symfony\Component\HttpKernel\KernelInterface $kernel
     */
    public function __construct(UserFacade $facade, KernelInterface $kernel)
    {
        $this->facade = $facade;
        $this->environment = $kernel->getEnvironment();
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('user@admin.com');
        $user->setName('admin');
        $user->setPassword('12345678910');

        $this->facade->create($user);

        if('test' === $this->environment) {
            $this->loadMany($manager);
        }
    }

    private function loadMany()
    {
        $users = [
            [
                'email' => 'user@test1.com',
                'name' => 'test1',
                'password' => 'test1pass',
            ],
            [
                'email' => 'user@test2.com',
                'name' => 'test2',
                'password' => 'test2pass',
            ]
        ];

        foreach ($users as $userData) {
            $user = new User();
            $user->setEmail($userData['email']);
            $user->setName($userData['name']);
            $user->setPassword($userData['password']);

            $this->facade->create($user);
        }
    }
}
