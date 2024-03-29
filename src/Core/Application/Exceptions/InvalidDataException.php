<?php
declare(strict_types = 1);

namespace App\Core\Application\Exceptions;

use App\Core\Domain\Functions\ClassName;

/**
 * Class InvalidDataException
 * @package App\Core\Application\Exceptions
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class InvalidDataException extends ApplicationException
{

    public function __construct(string $classpath = "", array $errors = [])
    {
        $classname = ClassName::getBaseName($classpath);

        parent::__construct("Provided values for $classname are not valid", $errors);
    }

}
