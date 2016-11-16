<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 16.11.16
 * Time: 17:29
 */

namespace App\EducationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class ContainsTypeFile extends Constraint
{

    public $message = 'The string "%string%" contains an illegal character: it can only contain letters or numbers.';
}