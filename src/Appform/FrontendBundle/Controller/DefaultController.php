<?php

namespace Appform\FrontendBundle\Controller;

use Appform\FrontendBundle\Entity\Applicant;
use Appform\FrontendBundle\Entity\AppUser;
use Appform\FrontendBundle\Entity\Document;
use Appform\FrontendBundle\Extensions\DriveHelper;
use Appform\FrontendBundle\Form\ApplicantType;
use Appform\FrontendBundle\Form\AppUserType;
use Appform\FrontendBundle\Form\PersonalInformationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller {

	public function indexAction( Request $request ) {

		$logger = $this->get('monolog.logger.accesslog');
		$this->logUser($request, $logger);

		$applicant    = new Applicant();
		$template = '@AppformFrontend/Default/form3Steps.html.twig';
		$session = $this->container->get('session');

		/* Get Referrer and set it to session */
		$utm_source = $request->get('utm_source') ? $request->get('utm_source') : false;
		$utm_medium = $request->get('utm_medium') ? $request->get('utm_medium') : false;
		$referer = $utm_source ? $utm_source : '';
		$referer .= $utm_source && $utm_medium ? '-' . $utm_medium : '';
		if ($referer != '') {
			$session->set('origin', $referer);
		}

		/* Get Referrer and set it to session */

		$form = $this->createForm(new ApplicantType( $this->get( 'Helper' ), $applicant, $referer));
		$data = array(
			'form' => $form->createView(),
		);

		return $this->render( $template, $data );
	}

	public function applyAction( Request $request ) {
		$session = $this->container->get('session');
		$response = '';
		header( 'Access-Control-Allow-Origin: *' );
		header( 'Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS' );

		$applicant = new Applicant();
		$form      = $this->createForm( new ApplicantType( $this->get( 'Helper' ), $applicant, null ) );

		if ( $request->isMethod( 'POST' ) ) {
			$form->handleRequest( $request );
			if ( $form->isValid() ) {
				$applicant  = $form->getData();

				if (in_array($applicant->getPersonalInformation()->getYearsLicenceSp(), [0, 1])) {
					return new Response( '<div class="error-message unit"><i class="fa fa-times"></i>
										We are sorry but at this time we cannot accept your information.
										The facilities of the HCEN Client Staffing Agencies require 2 years’
										 minimum experience in your chosen specialty. <br />
										Thank you
										</div>' );
				}
				if ($applicant->getPersonalInformation()->getDiscipline() == 5
						&& in_array($applicant->getPersonalInformation()->getSpecialtyPrimary(), [6,10, 57, 25])
				) {
					return new Response( '<div class="error-message unit"><i class="fa fa-times"></i>
										HCEN apologizes but at this time the HCEN Client Staffing agencies are only requesting
										Hospital based RN Specialties. HCEN cannot except at this time your Information Request
										for the following specialties; Home Health, Long Term Care and Dialysis
										</div>' );
				}

				if (in_array($applicant->getPersonalInformation()->getDiscipline(), [12,10])
						&& in_array($session->get('origin'), ['jobs2careers-cpc', 'ZipRecruiter-cpc', 'glassdoor'])) {
					return new Response( '<div class="error-message unit"><i class="fa fa-times"></i>
										HCEN apologizes but at this time the HCEN Client Staffing agencies are only requesting
										Physical Therapist and Occupational Therapist. HCEN cannot except at this time your
										More Information Request
										</div>' );
				}

				if ($session->get('origin')) {
					$applicant->setAppReferer($session->get('origin'));
				} else {
					$applicant->setAppReferer("Original");
				}

				if ($applicant->getFirstName() == $applicant->getLastName()) {
					$response =  '<div class="error-message unit"><i class="fa fa-times"></i>First Name and Last Name are simmilar</div>';
					return new Response( $response );
				}
				$repository = $this->getDoctrine()->getRepository( 'AppformFrontendBundle:Applicant' );

				/* Removed Unecessary loop */

				$randNum           = mt_rand( 100000, 999999 );
				$randNum = $repository->findOneBy( array( 'candidateId' => $randNum ) ) != $randNum ? $randNum : mt_rand( 100000, 999999 );

				$applicant->setCandidateId( $randNum );
				$applicant->setIp($request->getClientIp());
				$personalInfo = $applicant->getPersonalInformation();

				$helper       = $this->get( 'Helper' );
				/** Redirect to Specialty fix **/
				$discip = $personalInfo->getDiscipline() != 5 ? $helper->getDiscipline($personalInfo->getDiscipline()) : $helper->getSpecialty($personalInfo->getSpecialtyPrimary());
				$location = $helper->getStates($personalInfo->getState());

				$this->get('session')->getFlashBag()->add('discipline', $discip);
				$this->get('session')->getFlashBag()->add('location', $location);
				/** Redirect to Specialty fix **/

				$filename     = "HCEN - {$helper->getDisciplineShort($personalInfo->getDiscipline())}, ";
				if ($personalInfo->getDiscipline() == 5) {
					$filename .= "{$helper->getSpecialty($personalInfo->getSpecialtyPrimary())}, ";
				}
				$filename .= "{$applicant->getLastName()}, {$applicant->getFirstName()} - {$randNum}";
				$filename = str_replace('/', '-', $filename);
				$personalInfo->setApplicant( $applicant );

				/* fix of the hack */
				$personalInfo->setLicenseState(array_diff($personalInfo->getLicenseState(), array(0)));
				$personalInfo->setDesiredAssignementState(array_diff($personalInfo->getDesiredAssignementState(), array(0)));

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

					$mgRep = $this->getDoctrine()->getRepository( 'AppformBackendBundle:Mailgroup' );
					$mailPerOrigin = $mgRep->createQueryBuilder('m')
							->where('m.originsList LIKE :origin')
							->setParameter('origin', '%'.$applicant->getAppReferer().'%')
							->getQuery()->getOneOrNullResult();
					$getEmailToSend = $mailPerOrigin ? $mailPerOrigin->getEmail() : false;
					if ($this->sendReport($form, $getEmailToSend)) {
						$response =  '<div class="success-message unit"><i class="fa fa-check"></i>Your application has been sent successfully</div>';
					} else {
						$response =  '<div class="error-message unit"><i class="fa fa-times"></i>Something went wrong while sending message. Please resend form again</div>';
					}
				}
			} else {
				// Field error messages
				foreach ($this->getErrorMessages( $form ) as $field) {
					foreach ($field as $errorMsg) {
						if (is_array($errorMsg)) {
							foreach ($errorMsg as $message) {
							$response .= '<div class="error-message unit"><i class="fa fa-times"></i>'.$message.'</div><br />';
							}
						} else {
							$response .= '<div class="error-message unit"><i class="fa fa-times"></i>'.$errorMsg.'</div><br />';
						}
					}
				}
			}
		}
		$session->remove('origin');

		return new Response( $response );
	}

	public function successAction( Request $request){
		$referrer = $request->headers->get('referer');
		$logger = $this->get('monolog.logger.applog');
		$this->logUser($request, $logger);
		if ($referrer == "") {
			$data = ['access' => 'direct'];
		} else {
			$data = ['access' => 'form'];
		}

		return $this->render( 'AppformFrontendBundle:Default:form3StepsSuccess.html.twig', $data );
	}

	protected function generateFormFields ()
	{
		/* Data Generation*/
		$formTitles1 = array( 'id' => 'Candidate #' );
		$formTitles2 = array();
		$form1       = $this->createForm( new ApplicantType( $this->get( 'Helper' ), null, null ) );
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

	protected function sendReport( Form $form , $mailPerOrigin = false) {
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

		$this->get( 'knp_snappy.pdf' )->generateFromHtml(
			$this->renderView(
				'AppformFrontendBundle:Default:pdf.html.twig',
				$forPdf
			),$applicant->getDocument()->getUploadRootDir() . '/' . $applicant->getDocument()->getPdf());

		$objWriter = \PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel5' );

		$objWriter->save( $applicant->getDocument()->getUploadRootDir() . '/' . $applicant->getDocument()->getXls());
		$mailPerOrigin = $mailPerOrigin ? $mailPerOrigin : 'moreinfo@healthcaretravelers.com';

		$textBody = $this->renderView('AppformFrontendBundle:Default:email_template.html.twig', array('info' => $forPdf));

		$message = \Swift_Message::newInstance()
		                         ->setFrom( 'from@example.com' )
		                         ->setTo( 'daniyar.san@gmail.com' )
		                         ->addCc( $mailPerOrigin )
		                         ->addCc( 'HealthCareTravelers@Gmail.com' )
		                         ->setSubject( 'HCEN new Applicaton from More Info' )
		                         ->setBody( $textBody , 'text/html')
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

	public function saveFileToDrive($pathToFile)
	{
		$perMonthDirName = ($this->container->getParameter('drive.appname') .'-'. (new \DateTime())->format( 'F-Y' ));

		$service = new DriveHelper(
			$this->container->getParameter('drive.clientId'),
			$this->container->getParameter('drive.serviceaccname'), // Service Account Name
			$this->get('kernel')->getRootDir() . '/config/google.p12', // Auth file
			$perMonthDirName, // Main folder name
			$this->container->getParameter('drive.shareEmail') // Email to share with
		);

		$rootDirId = $service->getFileIdByName( $perMonthDirName );

		if( !$rootDirId ) {
			$rootDirId = $service->createFolder( $perMonthDirName );
			$service->setPermissions( $rootDirId, $this->container->getParameter('drive.shareEmail') );
		}

		return $service->createFileFromPath( $pathToFile, 'Log file', $rootDirId);
	}

	public function reportingTableAction( Request $request )
	{
		return $this->render( 'AppformFrontendBundle:Default:table.html.twig', array('test', 'test23'));
	}

	/**
	 * @param $referrer
	 */
	public function logUser($request, $logger)
	{
		$referrer = $request->headers->get('referer');
		if ($referrer == "")
			$reftext = "This page was accessed directly";
		else
			$reftext = $referrer;

		$ip = $_SERVER[ 'REMOTE_ADDR' ];
		$browser = $_SERVER[ 'HTTP_USER_AGENT' ];
		$output = false;
		$output = "Visitor IP address: " . $ip . "\r\n";
		$output .= "Browser (User Agent) Info: " . $browser . "\r\n";
		$output .= "Referrer: " . $reftext . "\r\n";
		$logger->info($output);
	}

}
