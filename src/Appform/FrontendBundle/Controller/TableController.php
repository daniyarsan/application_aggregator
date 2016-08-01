<?php

namespace Appform\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TableController extends Controller {

	public function indexAction( Request $request )
	{
		$filterRepo = $this->getDoctrine()->getRepository('AppformBackendBundle:Filter');
		$qb = $filterRepo->createQueryBuilder('f');
		$qb->orderBy('f.created', 'DESC')->setMaxResults(1);
		$filter = $qb->getQuery()->getSingleResult();
		if ($filter) {
			$applicantRepo = $this->getDoctrine()->getRepository('AppformFrontendBundle:Applicant')->findApplicantById($filter->getUserIds());
			$lastApplicant = reset($applicantRepo);
			$firstApplicant = end($applicantRepo);
			$dateRange['start'] = $lastApplicant->getCreated()->format('l m/d');
			$dateRange['end'] = $firstApplicant->getCreated()->format('l m/d');

			return $this->render( 'AppformFrontendBundle:Default:table.html.twig', ['data' => $applicantRepo, 'dateRange' => $dateRange]);
		}
	}

}
