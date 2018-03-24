<?php

namespace Appform\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{

	/**
	 * @Route("/", name="admin_dashboard")
	 */
	public function indexAction()
	{
		$applicantRepository = $this->getDoctrine()->getRepository('AppformFrontendBundle:Applicant');
		$visitorRep = $this->getDoctrine()->getRepository('AppformFrontendBundle:Visitor');

		$now = new \DateTime('now');
		$month = $now->format('m');
		$year = $now->format('Y');

		$thisMonthVisitors = $visitorRep->countThisMonthVisitors();

		return $this->render('AppformBackendBundle:Default:index.html.twig', array(
			'totalApplicants' => $applicantRepository->getCountAllApplicants(),
			'monthResult' => $applicantRepository->getPostsByMonth($year, $month),
			'todayResult' => $applicantRepository->getPostsByDay(),
			'thisMonthVisitors' => $thisMonthVisitors,
		));
	}
}
