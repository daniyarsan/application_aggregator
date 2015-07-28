<?php

namespace Appform\FrontendBundle\Reporting;

use Appform\FrontendBundle\Extensions\Helper;
use Appform\FrontendBundle\Form\ApplicantType;
use Appform\FrontendBundle\Form\PersonalInformationType;
use Symfony\Component\Form\Form;

class Reporting {

	protected $helper;
	protected $controller;

	public function __construct(Helper $helper)
	{
		$this->helper = $helper;
	}

	public function initController($controller)
	{
		$this->controller = $controller;
		return $this;
	}

	public function generateDataStructure() {
		/* Data Generation*/
		$formTitles1 = array( 'id' => 'Candidate #' );
		$formTitles2 = array();
		$form1       = $this->controller->createForm( new ApplicantType( $this->helper ) );
		$form2       = $this->controller->createForm( new PersonalInformationType( $this->helper ) );
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

	public function alphabeticStructure($fields) {
		$alphabet = array();
		$alphas   = range( 'A', 'Z' );
		$i        = 0;
		foreach ( $fields as $key => $value ) {
			$alphabet[ $key ] = $alphas[ $i ];
			$i ++;
		}
		return $alphabet;
	}

	public function sendReport( Form $form ) {
		$applicant    = $form->getData();
		$personalInfo = $applicant->getPersonalInformation();
		$helper       = $this->helper;

		$fields   = $this->generateDataStructure(   );
		$alphabet = $this->alphabeticStructure($fields);

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
}