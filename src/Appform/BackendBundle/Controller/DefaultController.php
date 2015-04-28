<?php

namespace Appform\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AppformBackendBundle:Default:index.html.twig');
    }
}
