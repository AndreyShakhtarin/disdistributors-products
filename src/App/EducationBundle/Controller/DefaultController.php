<?php

namespace App\EducationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AppEducationBundle:Default:index.html.twig');
    }
}
