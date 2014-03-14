<?php

namespace Ation\Bundle\ApplicationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AtionApplicationBundle:Default:index.html.twig');
    }
}
