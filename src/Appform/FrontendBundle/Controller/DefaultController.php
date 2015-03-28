<?php

namespace Appform\FrontendBundle\Controller;

use Appform\FrontendBundle\Entity\Applicant;
use Appform\FrontendBundle\Entity\AppUser;
use Appform\FrontendBundle\Entity\Document;
use Appform\FrontendBundle\Entity\PersonalInformation;
use Appform\FrontendBundle\Form\ApplicantType;
use Appform\FrontendBundle\Form\AppUserType;
use Appform\FrontendBundle\Form\PersonalInformationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\Form;
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
				$filename = $document->getApplicant()->getFirstName() . '_' . $document->getApplicant()->getLastName();
				$document->setPdf($document->getUploadRootDir().'/' .$filename.'.'.'pdf');
				$document->setXls($document->getUploadRootDir().'/' .$filename.'.'.'xls');
				$applicant->setDocument($document);
			}

			$em = $this->getDoctrine()->getManager();
			$em->persist( $document );
			$em->persist( $personalInfo );
			$em->persist( $applicant );
			$em->flush();

			$session = $this->get( 'session' );
			return $this->sendReport( $form );
			if ($this->sendReport( $form )) {
				$session->getFlashBag()->add( 'success', 'Your message has been sent successfully.' );
			} else {
				$session->getFlashBag()->add( 'error', 'Something went wrong. Please resend mail again' );
			}

			return $this->redirect( $this->generateUrl( 'appform_frontend_homepage' ) );
		}
		$data = array(
			'form' => $form->createView()
		);

		return $this->render( 'AppformFrontendBundle:Default:index.html.twig', $data );
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
					$data = $data ? 'Resume file available' : 'Resume file is not available';
				}
			} else {
				if ( method_exists( $personalInfo, $metodName ) ) {
					$data = $personalInfo->$metodName();
					$data = ( is_object( $data ) && get_class( $data ) == 'DateTime' ) ? $data->format( 'Y-m-d H:i:s' ) : $data;
					$data = ( is_object( $data ) && get_class( $data ) == 'Document' ) ? $data->format( 'Y-m-d H:i:s' ) : $data;
					$data = ( $key == 'state' ) ? $helper->getStates( $data ) : $data;
					$data = ( $key == 'discipline' ) ? $helper->getDiscipline( $data ) : $data;
					$data = ( $key == 'specialtyPrimary' ) ? $helper->getSpecialty( $data ) : $data;
					$data = ( $key == 'specialtySecondary' ) ? $helper->getSpecialty( $data ) : $data;
					if ( $key == 'isOnAssignement' || $key == 'isExperiencedTraveler' ) {
						$data = $data == true ? 'yes' : 'no';
					}
				}
			}
			$data           = $data ? $data : '';
			$forPdf[ $key ] = $data;
			$objPHPExcel->setActiveSheetIndex( 0 )
			            ->setCellValue( $alphabet[ $key ] . '1', $value )
			            ->setCellValue( $alphabet[ $key ] . '2', $data );
		}

		return $this->render( 'AppformFrontendBundle:Default:pdf.html.twig', $forPdf );


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
		                         ->setSubject( 'New Lead' )
		                         ->setBody( 'Please find new candidate Lead' )
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
		$pathToJs = $this->container->getParameter('kernel.root_dir').'/../web/widget/js';
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

		$finderJs = new Finder();
		$finderJs->files()->in($pathToJs);
		$jsFileSet = '';
		foreach ($finderJs as $key => $jsFile) {
			$jsFileSet .= '
			var '.str_replace('.js', '', $jsFile->getFilename()).'_link = document.createElement( "script" );
			'.str_replace('.js', '', $jsFile->getFilename()).'_link.type = "text/javascript";
			'.str_replace('.js', '', $jsFile->getFilename()).'_link.src = "http://'.$host.'/widget/js/'.$jsFile->getFilename().'";

			$("head").append('.str_replace('.js', '', $jsFile->getFilename()).'_link);
			';
		}

		$finder = new Finder();
		$finder->files()->in($widgetPath);
		foreach ($finder as $file) {
			$contents = $file->getContents();
		}

		$contents = str_replace('!css!', $cssFileSet, $contents);
		$contents = str_replace('!js!', $jsFileSet, $contents);
		$contents = str_replace('!baseurl!', $baseurl, $contents);

		$response = new Response($contents);
		$response->headers->set('Content-Type', 'application/x-javascript');
		return $response;
	}
}
