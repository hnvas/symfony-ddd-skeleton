<?php
declare(strict_types = 1);

namespace App\Core\Application\Exceptions;

use App\Core\Domain\Functions\Classname;

/**
 * Class NotFoundException
 * @package App\Core\Application\Exceptions
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class NotFoundException extends ApplicationException
{

    public function __construct(string $classpath = "")
    {
        $classname = Classname::getBaseName($classpath);

        parent::__construct("The resource $classname was not found", []);
    }

}
