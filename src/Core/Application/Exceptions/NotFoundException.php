<?php
declare(strict_types = 1);

namespace App\Core\Application\Exceptions;

use App\Core\Domain\Functions\Classname;

class NotFoundException extends ApplicationException
{

    public function __construct(string $classpath = "")
    {
        $classname = Classname::getBaseName($classpath);

        parent::__construct("The resource $classname was not found", []);
    }

}
