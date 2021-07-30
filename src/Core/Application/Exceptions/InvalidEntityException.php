<?php
declare(strict_types = 1);

namespace App\Core\Application\Exceptions;

use DomainException;

class InvalidEntityException extends DomainException
{

    public array $errors;

    public function __construct(string $classname = "entity", array $errors = [])
    {
        $this->errors = $errors;

        parent::__construct("Provided values for $classname are not valid");
    }

}
