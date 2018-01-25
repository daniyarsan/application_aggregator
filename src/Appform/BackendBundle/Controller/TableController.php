<?php

namespace Appform\BackendBundle\Controller;

use Appform\BackendBundle\Entity\Filter;
use Appform\BackendBundle\Form\TableType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Setting controller.
 *
 * @Route("/table")
 */

class TableController extends Controller {


	/**
	 * Lists all Setting entities.
	 *
	 * @Route("/", name="table")
	 * @Method("GET")
	 * @Template()
	 */
	public function indexAction( Request $request )
	{
		$filterRepo = $this->getDoctrine()->getRepository('AppformBackendBundle:Filter');
		$queryBuilder = $filterRepo->createQueryBuilder('f');
		$queryBuilder->orderBy('f.id', 'desc');

		$paginator = $this->get('knp_paginator');
		$pagination = null;

		try {
			$pagination = $paginator->paginate(
					$queryBuilder,
					$this->get('request')->query->get('page', 1),
					$this->get('request')->query->get('itemsPerPage', 20)
			);
		} catch (QueryException $ex) {
			$pagination = $paginator->paginate(
					$queryBuilder,
					1,
					$this->get('request')->query->get('itemsPerPage', 20)
			);
		}

		$data['pagination'] = $pagination;

		return $this->render( 'AppformBackendBundle:Table:index.html.twig', $data);

	}

	/**
	 * @Route("/", name="table_create")
	 * @Method("POST")
	 */
	public function createAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$tableData = $request->request->get('appform_backendbundle_table');

		$entity = $em->getRepository('AppformBackendBundle:Filter')->find($tableData['table']);

		if (!$entity) {
			$entity = new Filter();
		}
		$form = $this->createCreateForm($entity);
		$form->handleRequest($request);
		$applicants = array_filter($request->request->get('applicants'), function($value) { return  is_numeric($value); });
		$entity->setUserIds($applicants);

		$em->persist($entity);
		$em->flush();

		return $this->redirect($this->generateUrl('table'));
	}


	private function createCreateForm(Filter $entity)
	{
		$form = $this->createForm(new TableType(), $entity, array(
				'action' => $this->generateUrl('table_create'),
				'method' => 'POST',
		));

		$form->add('submit', 'submit', array('label' => 'Create'));

		return $form;
	}


}

/*
$qb = $filterRepo->createQueryBuilder('f');
$qb->orderBy('f.created', 'DESC')->setMaxResults(1);
$filter = $qb->getQuery()->getSingleResult();

if ($filter) {
	$applicantRepo = $this->getDoctrine()->getRepository('AppformFrontendBundle:Applicant')->findById($filter->getUserIds());
	$lastApplicant = reset($applicantRepo);
	$firstApplicant = end($applicantRepo);
	$dateRange['start'] = $lastApplicant->getCreated()->format('l m/d');
	$dateRange['end'] = $firstApplicant->getCreated()->format('l m/d');

	return $this->render( 'AppformFrontendBundle:Default:table.html.twig', ['data' => $applicantRepo, 'dateRange' => $dateRange]);
}*/