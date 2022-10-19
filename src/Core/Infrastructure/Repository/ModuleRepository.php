<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Repository;

use App\Core\Domain\Model\Module;
use App\Core\Domain\Model\User;
use App\Core\Domain\Repository\ModuleRepositoryInterface;
use App\Core\Domain\Repository\SearchableRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class ModuleRepository
 * @package App\Core\Infrastructure\Repository
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class ModuleRepository extends ServiceEntityRepository implements
    ModuleRepositoryInterface,
    SearchableRepositoryInterface
{
    use RepositoryBehaviorTrait, RepositorySearchableTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Module::class);
    }

    public function findByUser(User $user): array
    {
        $qb = $this->createQueryBuilder('m');
        $qb->join('m.permissions', 'p', Join::WITH)
            ->where($qb->expr()->in('p.role', $user->roles()));

        return $qb->getQuery()->getResult();
    }
}
