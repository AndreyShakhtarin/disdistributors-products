<?php

namespace App\EducationBundle\Validator\Constraints;
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 27.10.16
 * Time: 17:36
 */
use Symfony\Component\Validator\Constraint;

class ContainsAlphanumeric extends Constraint
{
    public $message = 'The string "%string%" contains an illegal character: it can only contain letters or numbers.';

    public function validatedBy()
    {
        return get_class($this).'Validator';
    }


}