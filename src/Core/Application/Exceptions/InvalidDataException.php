<?php
declare(strict_types = 1);

namespace App\Core\Application\Exceptions;

use App\Core\Domain\Functions\Classname;

class InvalidDataException extends ApplicationException
{

    public function __construct(string $classpath = "", array $errors = [])
    {
        $classname = Classname::getBaseName($classpath);

        parent::__construct("Provided values for $classname are not valid", $errors);
    }

}
