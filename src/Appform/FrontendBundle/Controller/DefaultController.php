<?php

namespace Appform\FrontendBundle\Controller;

use Appform\FrontendBundle\Entity\Applicant;
use Appform\FrontendBundle\Entity\AppUser;
use Appform\FrontendBundle\Entity\Document;
use Appform\FrontendBundle\Form\ApplicantType;
use Appform\FrontendBundle\Form\AppUserType;
use Appform\FrontendBundle\Form\PersonalInformationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller {

	public function indexAction( Request $request ) {
		$applicant    = new Applicant();
		$form = $this->createForm( new ApplicantType( $this->get( 'Helper' ), $applicant ) );
		$form->handleRequest( $request );

		if ( $form->isValid() ) {
			$applicant  = $form->getData();
			$repository = $this->getDoctrine()->getRepository('AppformFrontendBundle:Applicant');
			do {
				$randNum = mt_rand(100000, 999999);
				$candidateIdExists = $repository->findOneBy(array('candidateId' => $randNum));
			}
			while ($candidateIdExists);
			$applicant->setCandidateId($randNum);

			$personalInfo = $applicant->getPersonalInformation();
			$personalInfo->setApplicant( $applicant );

			if ($applicant->getDocument()) {
				$document = $applicant->getDocument();
				$document->setApplicant($applicant);
			} else {
				$document = new Document();
				$document->setApplicant($applicant);
				$applicant->setDocument($document);
			}
			//return $this->sendReport( $form );
			$filename = $document->getApplicant()->getFirstName() . '_' . $document->getApplicant()->getLastName();
			$document->setPdf($document->getUploadRootDir().'/' .$filename.'.'.'pdf');
			$document->setXls($document->getUploadRootDir().'/' .$filename.'.'.'xls');

			$em = $this->getDoctrine()->getManager();
			$em->persist( $document );
			$em->persist( $personalInfo );
			$em->persist( $applicant );
			$em->flush();

			$session = $this->get( 'session' );
			if ($this->sendReport( $form )) {
				$session->getFlashBag()->add( 'success', 'Your message has been sent successfully.' );
			} else {
				$session->getFlashBag()->add( 'error', 'Something went wrong. Please resend mail again' );
			}
			return $this->redirect( $this->generateUrl( 'appform_frontend_homepage' ) );
		}
		$data = array(
			'form' => $form->createView(),
			'lstates' => $this->get('Helper')->getLicenseStates(),
			'dastates' => $this->get('Helper')->getDaStates()
		);

		return $this->render( 'AppformFrontendBundle:Default:index.html.twig', $data );
	}

	public function iframeAction( Request $request ) {
		$applicant    = new Applicant();
		$form = $this->createForm( new ApplicantType( $this->get( 'Helper' )->setRequest($request), $applicant ) );
		$form->handleRequest( $request );

		if ( $form->isValid() ) {
			$applicant  = $form->getData();
			$repository = $this->getDoctrine()->getRepository('AppformFrontendBundle:Applicant');
			do {
				$randNum = mt_rand(100000, 999999);
				$candidateIdExists = $repository->findOneBy(array('candidateId' => $randNum));
			}
			while ($candidateIdExists);
			$applicant->setCandidateId($randNum);

			$personalInfo = $applicant->getPersonalInformation();
			$personalInfo->setApplicant( $applicant );

			if ($applicant->getDocument()) {
				$document = $applicant->getDocument();
				$document->setApplicant($applicant);
			} else {
				$document = new Document();
				$document->setApplicant($applicant);
				$applicant->setDocument($document);
			}
			//return $this->sendReport( $form );
			$filename = $document->getApplicant()->getFirstName() . '_' . $document->getApplicant()->getLastName();
			$document->setPdf($document->getUploadRootDir().'/' .$filename.'.'.'pdf');
			$document->setXls($document->getUploadRootDir().'/' .$filename.'.'.'xls');

			$em = $this->getDoctrine()->getManager();
			$em->persist( $document );
			$em->persist( $personalInfo );
			$em->persist( $applicant );
			$em->flush();

			$session = $this->get( 'session' );
			if ($this->sendReport( $form )) {
				$session->getFlashBag()->add( 'success', 'Your message has been sent successfully.' );
			} else {
				$session->getFlashBag()->add( 'error', 'Something went wrong. Please resend mail again' );
			}
			return $this->redirect( $this->generateUrl( 'appform_frontend_homepage' ) );
		}
		$data = array(
			'form' => $form->createView(),
			'lstates' => $this->get('Helper')->getLicenseStates(),
			'dastates' => $this->get('Helper')->getDaStates()
		);

		return $this->render( 'AppformFrontendBundle:Default:iframe.html.twig', $data );
	}

	public function widgetAction( Request $request ) {
		$response = array();

		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');

		$applicant    = new Applicant();
		$form = $this->createForm( new ApplicantType( $this->get( 'Helper' ), $applicant ) );

		if ( $request->isMethod( 'POST' ) ) {
			$form->handleRequest( $request );
			if ( $form->isValid() ) {
				$applicant  = $form->getData();
				$repository = $this->getDoctrine()->getRepository('AppformFrontendBundle:Applicant');
				do {
					$randNum = mt_rand(100000, 999999);
					$candidateIdExists = $repository->findOneBy(array('candidateId' => $randNum));
				}
				while ($candidateIdExists);
				$applicant->setCandidateId($randNum);
				$personalInfo = $applicant->getPersonalInformation();
				$helper       = $this->get( 'Helper' );
				$filename = "HCEN - {$helper->getSpecialty($personalInfo->getSpecialtyPrimary())}, {$applicant->getLastName()}, {$applicant->getFirstName()}-{$randNum}";

				$personalInfo->setApplicant( $applicant );

				if ($document = $applicant->getDocument()) {
					$document->setApplicant($applicant);
					$document->setPdf($filename.'.'.'pdf');
					$document->setXls($filename.'.'.'xls');

				} else {
					$document = new Document();
					$document->setApplicant($applicant);
					$document->setPdf($document->getUploadRootDir().'/' .$filename.'.'.'pdf');
					$document->setXls($document->getUploadRootDir().'/' .$filename.'.'.'xls');
					$applicant->setDocument($document);
				}

				$document->setFileName($filename);
				if ($repository->findOneBy(array('email' => $applicant->getEmail()))) {
					$response['error']['saving'] = 'You have already submitted form';
				} else {
					$em = $this->getDoctrine()->getManager();
					$em->persist( $document );
					$em->persist( $personalInfo );
					$em->persist( $applicant );
					$em->flush();

					if ($this->sendReport( $form )) {
						$response['success'] = 'Your message has been sent successfully';
					} else {
						$response['error']['sending'] = 'Something went wrong while sending message. Please resend form again.';
					}
				}
			} else {
				$response['error'] = $this->getErrorMessages($form);

			}
		}
		return new JsonResponse($response);
	}

	protected function sendReport( Form $form ) {
		$applicant    = $form->getData();
		$personalInfo = $applicant->getPersonalInformation();
		$helper       = $this->get( 'Helper' );

		/* Data Generation*/
		$formTitles1 = array( 'id' => 'Candidate #' );
		$formTitles2 = array();
		$form1       = $this->createForm( new ApplicantType( $this->get( 'Helper' ) ) );
		$form2       = $this->createForm( new PersonalInformationType( $this->get( 'Helper' ) ) );
		$children1   = $form1->all();
		$children2   = $form2->all();
		foreach ( $children1 as $child ) {
			$config = $child->getConfig();
			if ( $config->getOption( "label" ) != null ) {
				$formTitles1[ $child->getName() ] = $config->getOption( "label" );
			}
		}
		foreach ( $children2 as $child ) {
			$config = $child->getConfig();
			if ( $config->getOption( "label" ) != null ) {
				$formTitles2[ $child->getName() ] = $config->getOption( "label" );
			}
		}
		$fields   = array_merge( $formTitles1, $formTitles2 );
		$alphabet = array();
		$alphas   = range( 'A', 'Z' );
		$i        = 0;
		foreach ( $fields as $key => $value ) {
			$alphabet[ $key ] = $alphas[ $i ];
			$i ++;
		}
		/* Data Generation*/

		// Create new PHPExcel object
		$objPHPExcel = $this->get( 'phpexcel' )->createPHPExcelObject();
		// Set document properties
		$objPHPExcel->getProperties()->setCreator( "HealthcareTravelerNetwork" )
		            ->setLastModifiedBy( "HealthcareTravelerNetwork" )
		            ->setTitle( "Applicant Data" )
		            ->setSubject( "Applicant Document" );

		/* Filling in excel document */
		$forPdf = array();

		foreach ( $fields as $key => $value ) {
			$metodName = 'get' . $key;
			if ( method_exists( $applicant, $metodName ) ) {
				$data = $applicant->$metodName();
				$data = $data ? $data : '';
				if (is_object( $data ) && get_class( $data ) == 'Appform\FrontendBundle\Entity\Document') {
					$data = $data->getPath() ? 'Yes' : 'No';
				}
			} else {
				if ( method_exists( $personalInfo, $metodName ) ) {
					$data = $personalInfo->$metodName();
					$data = ( is_object( $data ) && get_class( $data ) == 'DateTime' ) ? $data->format( 'F d,Y' ) : $data;
					$data = ( is_object( $data ) && get_class( $data ) == 'Document' ) ? $data->format( 'F d,Y' ) : $data;
					$data = ( $key == 'state' ) ? $helper->getStates( $data ) : $data;
					$data = ( $key == 'discipline' ) ? $helper->getDiscipline( $data ) : $data;
					$data = ( $key == 'specialtyPrimary' ) ? $helper->getSpecialty( $data ) : $data;
					$data = ( $key == 'specialtySecondary' ) ? $helper->getSpecialty( $data ) : $data;
					$data = ( $key == 'yearsLicenceSp' ) ? $helper->getExpYears( $data ) : $data;
					$data = ( $key == 'yearsLicenceSs' ) ? $helper->getExpYears( $data ) : $data;
					$data = ( $key == 'assignementTime' ) ? $helper->getAssTime( $data ) : $data;
					$data = ( $key == 'licenseState' || $key == 'desiredAssignementState' ) ? implode(',', $data) : $data;
					if ( $key == 'isOnAssignement' || $key == 'isExperiencedTraveler' ) {
						$data = $data == true ? 'Yes' : 'No';
					}
				}
			}

			$data           = $data ? $data : '';
			$data           =  is_array($data) ? '' : $data;
			$forPdf[ 'candidateId' ] = $applicant->getCandidateId();

			$forPdf[ $key ] = $data;

			$objPHPExcel->setActiveSheetIndex( 0 )
			            ->setCellValue( $alphabet[ $key ] . '1', $value )
			            ->setCellValue( $alphabet[ $key ] . '2', $data );
		}

		//return $this->render( 'AppformFrontendBundle:Default:pdf.html.twig', $forPdf );

		$this->get( 'knp_snappy.pdf' )->generateFromHtml(
			$this->renderView(
				'AppformFrontendBundle:Default:pdf.html.twig',
				$forPdf
			),
			$applicant->getDocument()->getPdf()
		);

		$objWriter = \PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel5' );
		$objWriter->save( $applicant->getDocument()->getXls() );

		$message = \Swift_Message::newInstance()
		                         ->setFrom( 'from@example.com' )
		                         ->setTo( 'daniyar.san@gmail.com' )
		                         ->addCc( 'moreinfo@healthcaretravelers.com' )
		                         ->setSubject( 'HCEN Request for More Info' )
		                         ->setBody( 'Please find new candidate Lead. HCEN Request for More Info' )
		                         ->attach( \Swift_Attachment::fromPath( $applicant->getDocument()->getPdf() ))
		                         ->attach( \Swift_Attachment::fromPath( $applicant->getDocument()->getXls() ));

		if ($applicant->getDocument()->getPath()) {
			$message->attach( \Swift_Attachment::fromPath( $applicant->getDocument()->getPath() ));
		}
		return $this->get( 'mailer' )->send( $message );
	}

	public function getformAction (Request $request)
	{
		$applicant    = new Applicant();
		$form = $this->createForm( new ApplicantType( $this->get( 'Helper' ), $applicant ) );
		$data = array(
			'form' => $form->createView()
		);

		$html = $this->renderView('AppformFrontendBundle:Default:widget.html.twig', $data );
		$callBack = $request->get('callback').'(' .json_encode( array("html" => $html) ) . ');';
		$response = new Response($callBack);
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}

	public function getWidgetAction()
	{
		$baseurl = $this->get('router')->generate('appform_frontend_renderform', array(), true);
		$widgetPath = $this->container->getParameter('kernel.root_dir').'/../web/widget/dyn';
		$pathToCss = $this->container->getParameter('kernel.root_dir').'/../web/widget/css';
		$host = $this->getRequest()->getHost();

		$finderCss = new Finder();
		$finderCss->files()->in($pathToCss);
		$cssFileSet = '';
		foreach ($finderCss as $key => $cssFile) {
			$cssFileSet .= '
			var '.str_replace('.css', '', $cssFile->getFilename()).'_link = $("<link>", {
				rel: "stylesheet",
				type: "text/css",
				href: "http://'.$host.'/widget/css/'.$cssFile->getFilename().'"
			});
			'.str_replace('.css', '', $cssFile->getFilename()).'_link.appendTo("head");
			';
		}

		$finder = new Finder();
		$finder->files()->in($widgetPath);
		foreach ($finder as $file) {
			$contents = $file->getContents();
		}

		$contents = str_replace('!css!', $cssFileSet, $contents);
		$contents = str_replace('!baseurl!', $baseurl, $contents);

		$response = new Response($contents);
		$response->headers->set('Content-Type', 'application/x-javascript');
		return $response;
	}

	public function problemAction(Request $request)
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');

		$message = \Swift_Message::newInstance()
		                         ->setFrom( 'from@example.com' )
		                         ->setTo( 'daniyar.san@gmail.com' )
		                         ->setCc( 'Admin@HealthCareTravelers.com' )
		                         ->setSubject( 'Problem from the more info form' )
		                         ->setBody( $request->get('reason') );
		return new JsonResponse($this->get( 'mailer' )->send( $message ));
	}

	private function getErrorMessages(\Symfony\Component\Form\Form $form) {
		$errors = array();
		foreach ($form->getErrors() as $key => $error) {
			$template = $error->getMessageTemplate();
			$parameters = $error->getMessageParameters();

			foreach ($parameters as $var => $value) {
				$template = str_replace($var, $value, $template);
			}

			$errors[$key] = $template;
		}
		if ($form->count()) {
			foreach ($form as $child) {
				if (!$child->isValid()) {
					$errors[$child->getName()] = $this->getErrorMessages($child);
				}
			}
		}
		return $errors;
	}
}
