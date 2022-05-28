<?php
declare(strict_types = 1);

namespace App\Core\Domain\Repository;

interface Pageable
{
    public function total(): int;

    public function getSlice(int $offset, int $length): iterable;
}
