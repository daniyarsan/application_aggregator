<?php

namespace Appform\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TableController extends Controller {

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

		return $this->render( 'AppformFrontendBundle:Default:table.html.twig', ['data' => $applicantRepo, 'dateRange' => $dateRange]);
	}

}
