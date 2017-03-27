<?php

namespace Appform\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Users controller.
 *
 * @Route("/visitors")
 */
class VisitorsController extends Controller
{

	/**
	 * @Route("/", name="visitors")
	 * @Template()
	 */
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();

		$queryBuilder = $em->getRepository('AppformFrontendBundle:Visitor')->findAll();

		$paginator = $this->get('knp_paginator');
		$pagination = null;
		$paginatorOptions = array(
				'defaultSortFieldName' => 'i.id',
				'defaultSortDirection' => 'desc');

		try {
			$pagination = $paginator->paginate(
					$queryBuilder,
					$this->get('request')->query->get('page', 1),
					$this->get('request')->query->get('itemsPerPage', 20),
					$paginatorOptions
			);
		} catch (QueryException $ex) {
			$pagination = $paginator->paginate(
					$queryBuilder,
					1,
					$this->get('request')->query->get('itemsPerPage', 20),
					$paginatorOptions
			);
		}
		return array(
			'pagination' => $pagination
		);
	}

	/**
	 * @Route("/show")
	 * @Template()
	 */
	public function showAction()
	{
		return array(// ...
		);
	}


}
