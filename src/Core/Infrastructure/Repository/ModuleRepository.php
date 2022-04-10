<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Repository;

use App\Core\Domain\Model\Module;
use App\Core\Domain\Repository\ModuleRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ModuleRepository extends ServiceEntityRepository implements
    ModuleRepositoryInterface
{
    use RepositoryBehaviorTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Module::class);
    }
}
