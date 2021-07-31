<?php
declare(strict_types = 1);

namespace App\Core\Application\Exceptions;

use App\Core\Domain\Functions\Classname;

class EntityNotFoundException extends ApplicationException
{

    public function __construct(string $classpath = "")
    {
        $classname = Classname::getBaseName($classpath);

        parent::__construct("The entity $classname was not found", []);
    }

}
