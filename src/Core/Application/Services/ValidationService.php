<?php
declare(strict_types = 1);

namespace App\Core\Application\Services;

use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationService
{

    /**
     * @var \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * @var array
     */
    private array $errors;

    /**
     * ValidationService constructor.
     *
     * @param \Symfony\Component\Validator\Validator\ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
        $this->errors    = [];
    }

    public function isValid($object): bool
    {
        $violations = $this->validator->validate($object);

        if (empty($violations)) {
            return true;
        }

        $this->setErrors($violations);

        return false;
    }

    private function setErrors(ConstraintViolationList $violations): void
    {
        foreach ($violations as $violation) {
            $this->errors[ $violation->getPropertyPath() ] = $violation->getMessage();
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

}
