<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\DataFixtures;

use App\Core\Domain\Enum\ModuleEnum;
use App\Core\Domain\Model\Module;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class ModuleFixtures
 * @package App\Core\Infrastructure\DataFixtures
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class ModuleFixtures extends Fixture implements FixtureGroupInterface
{

    const CORE_MODULE_REFERENCE = 'core-module';

    public function load(ObjectManager $manager)
    {
        $module = new Module(
            ModuleEnum::CORE,
            true
        );

        $manager->persist($module);
        $manager->flush();

        $this->addReference(self::CORE_MODULE_REFERENCE, $module);
    }

    public static function getGroups(): array
    {
        return ['permission'];
    }
}
