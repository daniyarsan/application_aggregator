<?php

namespace Appform\BackendBundle\Controller;

use Appform\BackendBundle\Entity\Campaign;
use Appform\BackendBundle\Entity\Filter;
use Appform\BackendBundle\Form\CampaignType;
use Appform\FrontendBundle\Entity\PersonalInformation;
use Appform\FrontendBundle\Form\ApplicantType;
use Appform\FrontendBundle\Form\PersonalInformationType;
use Appform\BackendBundle\Form\SearchType;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Users controller.
 *
 * @Route("/users")
 */
class UserController extends Controller {

	public $limit = 20;

	/**
	 * @Route("/", name="user_list")
	 */
	public function indexAction( Request $request ) {

		$form = $this->createForm(new SearchType( $this->get( 'Helper' ), $this->getDoctrine()->getRepository( 'AppformFrontendBundle:Applicant' ),  new PersonalInformation()));
		$campaign = new Campaign();
		$campaignForm = $this->createForm(new CampaignType(), $campaign);

		if ( $request->request->get( 'search' ) ) {
			$likeField = $request->request->get( 'search' );
			$applicant = $this->getDoctrine()->getRepository( 'AppformFrontendBundle:Applicant' )->findApplicantById( $likeField );
		} else {
			if ( $request->query->get( 'sort' ) && $request->query->get( 'direction' ) ) {
				$sort      = $request->query->get( 'sort' );
				$direction = $request->query->get( 'direction' );
			} else {
				$sort      = 'id';
				$direction = 'DESC';
			}
			$searchData = false;
			if ($request->request->get( 'filter' )) {
				$searchData = $request->request->get( 'appform_frontendbundle_search');
				$applicant = $this->getDoctrine()->getRepository( 'AppformFrontendBundle:Applicant' )->getUsersPerFilter( $searchData, $sort, $direction );
				$this->limit = count($applicant) ? count($applicant) : 1;  // Show all users after the filtering
			} else {
				$applicant = $this->getDoctrine()->getRepository( 'AppformFrontendBundle:Applicant' )->getUsers( $sort, $direction );
			}
		}

		$paginator = $this->get( 'knp_paginator' );
		$pagination = $paginator->paginate(
			$applicant,
			$this->get( 'request' )->query->get( 'page', 1 ),
			$this->limit
		);

		return $this->render( 'AppformBackendBundle:User:index.html.twig', array(
				'pagination' => $pagination,
				'form' => $form->createView(),
				'campaignForm' => $campaignForm->createView(),
				'counter' => count($applicant)));
	}

	/**
	 * @Route("/bulk-action", name="user_bulk_action")
	 * @Method("POST")
	 */
	public function bulkAction( Request $request )
	{
		$em = $this->getDoctrine()->getManager();
		if (!is_array($request->get('applicants'))) {
			$this->get('session')->getFlashBag()->add('error', 'Please select Categories');
		} else {
			switch ($request->get('action')) {
				case 'delete':
					foreach (array_keys($request->get('applicants')) as $id) {
						$applicant = $this->getDoctrine()->getRepository( 'AppformFrontendBundle:Applicant' )->find( $id );
						if (!empty($applicant)) {
							$em->remove($applicant);
						}
					}
					$this->get('session')->getFlashBag()->add('message', 'Users have been removed');
					break;

				case 'regenerate':
					foreach (array_keys($request->get('applicants')) as $id) {
						$applicant = $this->getDoctrine()->getRepository( 'AppformFrontendBundle:Applicant' )->find( $id );
						if (!empty($applicant)) {
							$this->sendReport($applicant);
						}
					}
					$this->get('session')->getFlashBag()->add('message', 'Applicants have been regenerated');
					break;

				case 'generateReport':
					// Create new PHPExcel object
					$objPHPExcel = $this->get( 'phpexcel' )->createPHPExcelObject();
					// Set document properties
					$objPHPExcel->getProperties()->setCreator( "HealthcareTravelerNetwork" )
							->setLastModifiedBy( "HealthcareTravelerNetwork" )
							->setTitle( "Applicant Report" )
							->setSubject( "Applicant Document" );

					$fields = $this->getFields();
					$objPHPExcel = $this->setExcelHeader($fields, $objPHPExcel);

					foreach (array_keys($request->get('applicants')) as $key => $id) {
						$applicant = $this->getDoctrine()->getRepository('AppformFrontendBundle:Applicant')->find($id);
						if (!$applicant) {
							throw new EntityNotFoundException("Page not found");
						}
						$objPHPExcel = $this->prepareDataForExcel($fields, $applicant, $objPHPExcel, $key);
					}
					$objWriter = \PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel5' );
					$objWriter->save( $applicant->getDocument()->getUploadRootDir() . '/../reports/report.xls');

					$this->get('session')->getFlashBag()->add('uploadable', array('type' => 'success', 'title' => '/reports/report.xls', 'message' => 'Report is generated'));
					break;

				case 'generateReportTable':
						$em = $this->getDoctrine()->getEntityManager();
						$filter = new Filter();
						$filter->setUserIds(array_keys($request->get('applicants')));
						$em->persist($filter);
						$em->flush();
					$this->get('session')->getFlashBag()->add('message', 'Table have been generated');
					break;
			}
		}
		$em->flush();

		return $this->redirect($this->generateUrl('user_list'));
	}

	/**
	 * Displays a form to edit an existing User entity.
	 *
	 * @Route("/edit/{id}", name="user_edit")
	 * @Method("GET")
	 * @Template()
	 */
	public function editAction( $id ) {

		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('AppformFrontendBundle:Applicant')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find User entity.');
		}

		$editForm = $this->createForm( new ApplicantType( $this->get( 'Helper' ), $entity ) );

		return array(
			'form' => $editForm->createView(),
			'entity' => $entity);
	}

	/**
	 * Edits an existing Category entity.
	 *
	 * @Route("/{id}", name="user_update")
	 * @Method("POST")
	 * @Template("BackendBundle:User:edit.html.twig")
	 */
	public function updateAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('AppformFrontendBundle:Applicant')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find User entity.');
		}

		$editForm = $this->createForm( new ApplicantType( $this->get( 'Helper' ), $entity ) );
		$editForm->submit($request);

		if ($editForm->isValid()) {
			$em->persist($entity);
			$em->flush();
			$this->get('session')->getFlashBag()->add('message', 'User was successfully saved.');
		}
		return array(
			'entity'      => $entity,
			'form'   => $editForm->createView(),
		);
	}

	/**
	 * @Route("/download/{filename}", name="user_download")
	 *
	 */
	public function downloadAction( $filename ) {
		$request = $this->get('request');
		$path = $this->get('kernel')->getRootDir(). "/../web/resume/";
		$content = file_get_contents($path.$filename);

		$response = new Response();
		//set headers
		$response->headers->set('Content-Type', 'mime/type');
		$response->headers->set('Content-Disposition', 'attachment;filename="'.$filename);

		$response->setContent($content);
		return $response;
	}

	public function getFields() {
		$em      = $this->getDoctrine()->getManager();
		$fields  = $em->getClassMetadata( 'AppformFrontendBundle:Applicant' )->getFieldNames();
		$fields1 = $em->getClassMetadata( 'AppformFrontendBundle:PersonalInformation' )->getFieldNames();

		return array(
			"id",
			"candidateId",
			"firstName",
			"lastName",
			"email",
			"created",
			"phone",
			"state",
			"discipline",
			"licenseState",
			"specialtyPrimary",
			"yearsLicenceSp",
			"specialtySecondary",
			"yearsLicenceSs",
			"desiredAssignementState",
			"isExperiencedTraveler",
			"isOnAssignement",
			"assignementTime",
			"question",
			"completion",
			"resume",
			"appReferer",
			'ip'
		);

		return array_merge( $fields, $fields1 );
	}

	public function setExcelHeader( $fields, $objPHPExcel ){
		// prepare alphabet counter
		$alphabet = array();
		$alphas   = range( 'A', 'Z' );
		$i        = 0;
		foreach ( $fields as $key => $value ) {
			$alphabet[ $key ] = $alphas[ $i ];
			$i ++;
		}
		foreach ( $fields as $key => $value ) {
			$objPHPExcel->setActiveSheetIndex( 0 )
			            ->setCellValue( $alphabet[ $key ] . '1', $value );
		}
		return $objPHPExcel;
	}


	protected function sendReport( $applicant ) {

		$personalInfo = $applicant->getPersonalInformation();
		$helper       = $this->get( 'Helper' );

		/* Data Generation*/
		$formTitles1 = array( 'id' => 'Candidate #', 'firstName' => 'First Name', 'lastName' => "Last Name", "email" => "Email" );
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

	protected function prepareDataForExcel( $fields, $applicant, $objPHPExcel, $counter ) {
		$personalInfo = $applicant->getPersonalInformation();

		$helper = $this->get('helper');
		$counter +=2;

		// prepare alphabet counter
		$alphabet = array();
		$alphas   = range( 'A', 'Z' );
		$i        = 0;
		foreach ( $fields as $key => $value ) {
			$alphabet[ $key ] = $alphas[ $i ];
			$i ++;
		}

		foreach ( $fields as $key => $value ) {
			$metodName = 'get' . ucfirst($value);
			if ( method_exists( $applicant, $metodName ) ) {
				$data = $applicant->$metodName();
				$data = ( is_object( $data ) && get_class( $data ) == 'DateTime' ) ? $data->format( 'm/d/Y H:i:s' ) : $data;
				$data = $data ? $data : '';
				if ( is_object( $data ) && get_class( $data ) == 'Appform\FrontendBundle\Entity\Document' ) {
					$data = $data->getPath() ? 'Yes' : 'No';
				}
			} else if ( method_exists( $personalInfo, $metodName ) ) {
				$data = $personalInfo->$metodName();
				$data = ( is_object( $data ) && get_class( $data ) == 'DateTime' ) ? $data->format( 'F d,Y' ) : $data;
				$data = ( is_object( $data ) && get_class( $data ) == 'Document' ) ? $data->format( 'F d,Y' ) : $data;
				$data = ( $value == 'state' ) ? $helper->getStates( $data ) : $data;
				$data = ( $value == 'discipline' ) ? $helper->getDiscipline( $data ) : $data;
				$data = ( $value == 'specialtyPrimary' ) ? $helper->getSpecialty( $data ) : $data;
				$data = ( $value == 'specialtySecondary' ) ? $helper->getSpecialty( $data ) : $data;
				$data = ( $value == 'yearsLicenceSp' ) ? $helper->getExpYears( $data ) : $data;
				$data = ( $value == 'yearsLicenceSs' ) ? $helper->getExpYears( $data ) : $data;
				$data = ( $value == 'assignementTime' ) ? $helper->getAssTime( $data ) : $data;
				if (is_array($data)) {
					$data = ( $value == 'desiredAssignementState' ) ? implode(',', ($data)) : $data;
				}

				//$data = ( $value == 'licenseState' || $value == 'desiredAssignementState' ) ? implode( ',', $data ) : $data;
				if ( $value == 'isOnAssignement' || $value == 'isExperiencedTraveler' ) {
					$data = $data == true ? 'Yes' : 'No';
				}
			} else {
				$document = $applicant->getDocument();
				$data = $document->getPath() ?  'Yes' : 'No';
			}
			$data = $data ? $data : '';
			$data = is_array( $data ) ? '' : $data;

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue( $alphabet[ $key ] . (string)$counter, $data );
		}
		return $objPHPExcel;
	}
}
