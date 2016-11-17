<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 16.11.16
 * Time: 19:04
 */

namespace App\EducationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsTypeFileValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint)
    {
        if (!preg_match('/^[a-zA-Z0-9]+$/', $value, $matches)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%string%', $value)
                ->addViolation();
        }
    }

    public static function TypeFileValidator($value)
    {

        self::validate($value);
    }
}