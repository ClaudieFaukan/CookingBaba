<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class BanWordValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var BanWord $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        $valueLower = strtolower($value);

        foreach ($constraint->banWords as $banWord) {
            if (str_contains($valueLower, $banWord)) {
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ banWord }}', $banWord)
            ->addViolation();
            }
        }
    }
}
