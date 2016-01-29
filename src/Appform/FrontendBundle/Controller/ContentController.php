<?php

namespace Appform\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ContentController extends Controller
{
	public function indexAction() {
		return $this->render('AppformFrontendBundle:Content:index.html.twig');
	}
}
