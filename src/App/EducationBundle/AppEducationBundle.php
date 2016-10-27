<?php

namespace App\EducationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppEducationBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
