<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\DataFixtures;

use App\Core\Application\Services\Facades\UserFacade;
use App\Core\Domain\Model\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{

    /**
     * @var \App\Core\Application\Services\Facades\UserFacade
     */
    private UserFacade $facade;

    /**
     * @param \App\Core\Application\Services\Facades\UserFacade $facade
     */
    public function __construct(UserFacade $facade)
    {
        $this->facade = $facade;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('user@admin.com');
        $user->setName('admin');
        $user->setPassword('12345678910');

        $this->facade->create($user);
    }
}
