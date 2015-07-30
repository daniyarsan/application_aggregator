<?php

namespace Appform\FrontendBundle\Controller;

use Appform\FrontendBundle\Entity\Applicant;
use Appform\FrontendBundle\Entity\AppUser;
use Appform\FrontendBundle\Entity\Document;
use Appform\FrontendBundle\Form\ApplicantType;
use Appform\FrontendBundle\Form\AppUserType;
use Appform\FrontendBundle\Form\PersonalInformationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller {

	public function indexAction() {
		return $this->redirect('/form');
	}

	public function formAction( Request $request ) {
		$applicant    = new Applicant();
		$form = $this->createForm( new ApplicantType( $this->get( 'Helper' ), $applicant ) );
		$form->handleRequest( $request );
		$data = array(
			'form' => $form->createView());
		return $this->render( '@AppformFrontend/Default/form.html.twig', $data );
	}

	public function applyAction( Request $request ) {
		$response = '';
		header( 'Access-Control-Allow-Origin: *' );
		header( 'Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS' );

		$applicant = new Applicant();
		$form      = $this->createForm( new ApplicantType( $this->get( 'Helper' ), $applicant ) );

		if ( $request->isMethod( 'POST' ) ) {
			$form->handleRequest( $request );
			if ( $form->isValid() ) {
				$applicant  = $form->getData();
				$repository = $this->getDoctrine()->getRepository( 'AppformFrontendBundle:Applicant' );
				do {
					$randNum           = mt_rand( 100000, 999999 );
					$candidateIdExists = $repository->findOneBy( array( 'candidateId' => $randNum ) );
				} while ( $candidateIdExists );
				$applicant->setCandidateId( $randNum );
				$personalInfo = $applicant->getPersonalInformation();
				$helper       = $this->get( 'Helper' );
				$filename     = "HCEN-{$helper->getSpecialty($personalInfo->getSpecialtyPrimary())}-{$applicant->getLastName()}-{$applicant->getFirstName()}-{$randNum}";
				$filename = str_replace('/', '-', $filename);
				$personalInfo->setApplicant( $applicant );

				if ( $document = $applicant->getDocument() ) {
					$document->setApplicant( $applicant );
					$document->setPdf( $filename . '.' . 'pdf' );
					$document->setXls( $filename . '.' . 'xls' );
				} else {
					$document = new Document();
					$document->setApplicant( $applicant );
					$document->setPdf( $filename . '.' . 'pdf' );
					$document->setXls( $filename . '.' . 'xls' );
					$applicant->setDocument( $document );
				}

				$document->setFileName( $filename );
				if ( $repository->findOneBy( array( 'email' => $applicant->getEmail() ) ) ) {
					$response =  '<div class="error-message unit"><i class="fa fa-times"></i>Such application already exists in database</div>';
				} else {
					$em = $this->getDoctrine()->getManager();
					$em->persist( $document );
					$em->persist( $personalInfo );
					$em->persist( $applicant );
					$em->flush();

					if ( $this->sendReport( $form ) ) {
						$response =  '<div class="success-message unit"><i class="fa fa-check"></i>Your application has been sent successfully</div>';
					} else {
						$response =  '<div class="error-message unit"><i class="fa fa-times"></i>Something went wrong while sending message. Please resend form again</div>';
					}
				}
			} else {
				// Field error messages
/*				foreach ($this->getErrorMessages( $form ) as $errorMsg) {
					$response .= '<div class="error-message unit"><i class="fa fa-times"></i>'.$errorMsg.'</div><br />';
				}*/
			}
		}

		return new Response( $response );
	}

	public function directAction( Request $request ) {
		$applicant = new Applicant();
		$form = $this->createForm( new ApplicantType( $this->get( 'Helper' )->setRequest($request), $applicant ) );
		$form->handleRequest( $request );
		$session = $this->get( 'session' );
		if ( $request->isMethod( 'POST' ) ) {
			if ( $form->isValid() ) {
				$applicant  = $form->getData();
				$repository = $this->getDoctrine()->getRepository( 'AppformFrontendBundle:Applicant' );
				if ( $repository->findOneBy( array( 'email' => $applicant->getEmail()))) {
					$session->getFlashBag()->add( 'error', 'Such application already exists in database.' );
				} else {
					do {
						$randNum           = mt_rand( 100000, 999999 );
						$candidateIdExists = $repository->findOneBy( array( 'candidateId' => $randNum ) );
					} while ( $candidateIdExists );
					$applicant->setCandidateId( $randNum );

					$personalInfo = $applicant->getPersonalInformation();
					$helper       = $this->get( 'Helper' );
					$filename     = "HCEN-{$helper->getSpecialty($personalInfo->getSpecialtyPrimary())}-{$applicant->getLastName()}-{$applicant->getFirstName()}-{$randNum}";
					$filename = str_replace('/', '-', $filename);
					$personalInfo->setApplicant( $applicant );

					if ( $document = $applicant->getDocument() ) {
						$document->setApplicant( $applicant );
						$document->setPdf( $filename . '.' . 'pdf' );
						$document->setXls( $filename . '.' . 'xls' );
					} else {
						$document = new Document();
						$document->setApplicant( $applicant );
						$document->setPdf($filename . '.' . 'pdf' );
						$document->setXls($filename . '.' . 'xls' );
						$applicant->setDocument($document);
					}
					$document->setFileName( $filename );

					$em = $this->getDoctrine()->getManager();
					$em->persist( $document );
					$em->persist( $personalInfo );
					$em->persist( $applicant );
					$em->flush();

					if ( $this->sendReport( $form ) ) {
						$session->getFlashBag()->add( 'success', 'Your application has been sent successfully.' );
					} else {
						$session->getFlashBag()->add( 'error', 'Something went wrong. Please resend mail again' );
					}
					return $this->redirect( $this->generateUrl( 'appform_frontend_homepage' ). "?status=success" );
				}
			} else {
				$session->getFlashBag()->add( 'error', 'Correct Field errors below' );
			}
		}
		$data = array(
			'form'     => $form->createView(),
			'status'=> $request->get('status'));

		return $this->render( 'AppformFrontendBundle:Default:form.html.twig', $data );
	}

	protected function generateFormFields ()
	{
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
		return array_merge( $formTitles1, $formTitles2 );
	}

	protected function generateAlphabetic($fields)
	{
		$alphabet = array();
		$alphas   = range( 'A', 'Z' );
		$i        = 0;
		foreach ( $fields as $key => $value ) {
			$alphabet[ $key ] = $alphas[ $i ];
			$i ++;
		}

		return $alphabet;
	}
	protected function sendReport( Form $form ) {
		$applicant    = $form->getData();
		$personalInfo = $applicant->getPersonalInformation();
		$helper       = $this->get( 'Helper' );


		$fields = $this->generateFormFields();

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
				if ( is_object( $data ) && get_class( $data ) == 'Appform\FrontendBundle\Entity\Document' ) {
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
					$data = ( $key == 'licenseState' || $key == 'desiredAssignementState' ) ? implode( ',', $data ) : $data;
					if ( $key == 'isOnAssignement' || $key == 'isExperiencedTraveler' ) {
						$data = $data == true ? 'Yes' : 'No';
					}
				}
			}

			$data                  = $data ? $data : '';
			$data                  = is_array( $data ) ? '' : $data;
			$forPdf['candidateId'] = $applicant->getCandidateId();

			$forPdf[ $key ] = $data;

			$alphabet = $this->generateAlphabetic($fields);
			$objPHPExcel->setActiveSheetIndex( 0 )
			            ->setCellValue( $alphabet[ $key ] . '1', $value )
			            ->setCellValue( $alphabet[ $key ] . '2', $data );
		}

/*		$this->get( 'knp_snappy.pdf' )->generateFromHtml(
			$this->renderView(
				'AppformFrontendBundle:Default:pdf.html.twig',
				$forPdf
			),$applicant->getDocument()->getUploadRootDir() . '/' . $applicant->getDocument()->getPdf());*/

		$objWriter = \PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel5' );

		$objWriter->save( $applicant->getDocument()->getUploadRootDir() . '/' . $applicant->getDocument()->getXls());

		$message = \Swift_Message::newInstance()
		                         ->setFrom( 'from@example.com' )
		                         ->setTo( 'daniyar.san@gmail.com' )
		                         ->addCc( 'moreinfo@healthcaretravelers.com' )
		                         ->setSubject( 'HCEN Request for More Info' )
		                         ->setBody( 'Please find new candidate Lead. HCEN Request for More Info' )
		                         ->attach( \Swift_Attachment::fromPath( $applicant->getDocument()->getUploadRootDir() . '/' . $applicant->getDocument()->getPdf() ) )
		                         ->attach( \Swift_Attachment::fromPath( $applicant->getDocument()->getUploadRootDir() . '/' . $applicant->getDocument()->getXls() ) );

		if ( $applicant->getDocument()->getPath() ) {
			$message->attach( \Swift_Attachment::fromPath( $applicant->getDocument()->getUploadRootDir() . '/' . $applicant->getDocument()->getPath()) );
		}

		return $this->get( 'mailer' )->send( $message );
	}

	private function getErrorMessages( \Symfony\Component\Form\Form $form ) {
		$errors = array();
		foreach ( $form->getErrors() as $key => $error ) {
			$template   = $error->getMessageTemplate();
			$parameters = $error->getMessageParameters();
			foreach ( $parameters as $var => $value ) {
				$template = str_replace( $var, $value, $template );
			}
			$errors[ $key ] = $template;
		}
		if ( $form->count() ) {
			foreach ( $form as $child ) {
				if ( ! $child->isValid() ) {
					$errors[ $child->getName() ] = $this->getErrorMessages( $child );
				}
			}
		}
		return $errors;
	}
}
