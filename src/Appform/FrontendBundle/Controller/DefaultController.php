<?php

namespace Appform\FrontendBundle\Controller;

use Appform\FrontendBundle\Entity\Applicant;
use Appform\FrontendBundle\Entity\AppUser;
use Appform\FrontendBundle\Entity\PersonalInformation;
use Appform\FrontendBundle\Form\ApplicantType;
use Appform\FrontendBundle\Form\AppUserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller
{
	public function indexAction(Request $request)
	{
		$applicant = new Applicant();
		$personalInfo = new PersonalInformation();
		$dir = '../web/resume/';

		$applicant->setPersonalInformation($personalInfo);
		$form = $this->createForm(new ApplicantType($this->get('Helper'), $applicant));
		$form->handleRequest($request);

		if ($form->isValid()) {
			$applicant = $form->getData();
			$personalInfo = $applicant->getPersonalInformation();
			$personalInfo->setApplicant($applicant);

			$resume = $personalInfo->getResume();

			$personalInfo->getResume()->move($dir, $applicant->getFirstName() . '_' . $applicant->getLastName().'.'. $resume->getClientOriginalExtension() );

			$em = $this->getDoctrine()->getManager();
			$em->persist($personalInfo);
			$em->persist($applicant);
			$em->flush();

			$session = $this->get('session');
			$session->getFlashBag()->add('success', 'Your message has been sent successfully.');
			return $this->redirect($this->generateUrl('appform_frontend_homepage'));
		}
		$data = array(
			'form' => $form->createView()
		);
		return $this->render('AppformFrontendBundle:Default:index.html.twig', $data);
	}
}
