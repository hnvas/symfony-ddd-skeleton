<?php
declare(strict_types = 1);

namespace App\Core\Application\Services\CRUD;

use App\Core\Application\Exceptions\InvalidDataException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

trait Validatable
{

    /**
     * @param \Symfony\Component\Validator\Validator\ValidatorInterface $validator
     * @param $object
     *
     * @throws \App\Core\Application\Exceptions\InvalidDataException
     */
    protected function validate(ValidatorInterface $validator, $object): void
    {
        $violations = $validator->validate($object);

        if (!count($violations)) {
            return;
        }

        $errors = $this->collectErrors($violations);

        throw new InvalidDataException(get_class($object), $errors);
    }

    protected function collectErrors(ConstraintViolationListInterface $violations): array
    {
        $errors = [];

        foreach ($violations as $violation) {
            $errors[ $violation->getPropertyPath() ] = $violation->getMessage();
        }

        return $errors;
    }
}
