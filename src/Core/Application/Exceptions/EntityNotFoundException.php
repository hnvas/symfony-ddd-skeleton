<?php
declare(strict_types = 1);

namespace App\Core\Application\Exceptions;

use Doctrine\ORM\EntityNotFoundException as DoctrineEntityNotFoundException;

class EntityNotFoundException extends DoctrineEntityNotFoundException
{

    public function __construct(string $classname = "")
    {
        parent::__construct("The entity $classname was not found");
    }

}
