<?php
declare(strict_types = 1);

namespace App\Core\Application\Exceptions;

use App\Core\Application\Functions\Classname;

class InvalidEntityException extends ApplicationException
{

    public function __construct(string $classpath = "", array $errors = [])
    {
        $classname = Classname::getBaseName($classpath);

        parent::__construct("Provided values for $classname are not valid", $errors);
    }

}
