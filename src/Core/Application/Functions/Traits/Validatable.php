<?php
declare(strict_types = 1);

namespace App\Core\Application\Functions\Traits;

use App\Core\Application\Exceptions\InvalidEntityException;
use App\Core\Application\Functions\Classname;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

trait Validatable
{

    /**
     * @param \Symfony\Component\Validator\Validator\ValidatorInterface $validator
     * @param $object
     *
     * @throws InvalidEntityException
     */
    protected function validate(ValidatorInterface $validator, $object): void
    {
        $violations = $validator->validate($object);

        if (empty($violations)) {
            return;
        }

        $errors = $this->collectErrors($violations);

        throw new InvalidEntityException(get_class($object), $errors);
    }

    /**
     * @param \Symfony\Component\Validator\ConstraintViolationList $violations
     *
     * @return array
     */
    protected function collectErrors(ConstraintViolationList $violations): array
    {
        $errors = [];

        foreach ($violations as $violation) {
            $errors[ $violation->getPropertyPath() ] = $violation->getMessage();
        }

        return $errors;
    }
}
