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
		$em = $this->getDoctrine()->getManager();

		$form = $this->createForm(new ApplicantType($this->get('Helper'), $applicant));
		$form->handleRequest($request);

		if ($form->isValid()) {
			$applicant->setFirstName('test');
			$applicant->setMiddleName('test');
			$applicant->setLastName('test');
			$applicant->setEmail('test');
			$applicant->setPersonalInformation(new PersonalInformation());

			$em->persist($applicant);
			$em->flush();

			$session = $this->get('session');
			$session->getFlashBag()->add('success', 'Ваше сообщение успешно отправлено. Спасибо.');

			return $this->redirect($this->generateUrl('appform_frontend_homepage'));
		}

		$data = [
			'form' => $form->createView()
		];
		return $this->render('AppformFrontendBundle:Default:index.html.twig', $data);
	}
}
