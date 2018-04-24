<?php

namespace Appform\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Users controller.
 *
 * @Route("/table")
 */

class TableController extends Controller {

	/**
	 * Table with month application report.
	 *
	 * @Route("/{id}", name="appform_frontend_table")
	 * @Method("GET")
	 */
	public function indexAction($id, Request $request )
	{
		$filter = $this->getDoctrine()->getRepository('AppformBackendBundle:Filter')->find($id);

		if (!$filter) {
			throw new NotFoundHttpException('Table is not found.');
		}

		$applicantRepo = $this->getDoctrine()->getRepository('AppformFrontendBundle:Applicant')->findById($filter->getUserIds());
		$lastApplicant = reset($applicantRepo);
		$firstApplicant = end($applicantRepo);
		$dateRange['start'] = $lastApplicant->getCreated()->format('l m/d');
		$dateRange['end'] = $firstApplicant->getCreated()->format('l m/d');

		return $this->render( 'AppformFrontendBundle:Default:table.html.twig', ['tableName' => $filter->getName(), 'data' => $applicantRepo, 'dateRange' => $dateRange]);
	}

}
