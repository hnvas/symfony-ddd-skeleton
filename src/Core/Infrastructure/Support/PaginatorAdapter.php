<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Support;

use App\Core\Domain\Repository\Pageable;
use Pagerfanta\Doctrine\ORM\QueryAdapter;

/**
 * Class PaginatorAdapter
 * @package App\Core\Infrastructure\Support
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class PaginatorAdapter extends QueryAdapter implements Pageable
{
    public function total(): int
    {
        return $this->getNbResults();
    }
}
