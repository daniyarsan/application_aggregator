<?php

namespace Appform\BackendBundle\Controller;

use Appform\BackendBundle\Entity\Filter;
use Appform\BackendBundle\Form\CampaignType;
use Appform\FrontendBundle\Entity\Applicant;
use Appform\FrontendBundle\Form\ApplicantType;
use Appform\FrontendBundle\Form\PersonalInformationType;
use Appform\BackendBundle\Form\SearchType;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Query\QueryException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
	public $filename = false;

	/**
	 * @Route("/", name="user_list")
	 */
	public function indexAction( Request $request ) {

		$em = $this->getDoctrine()->getManager();

		$searchForm = $this->createSearchForm();
		$campaignForm = $this->createCampaignForm();
		$searchForm->handleRequest($request);

		if ($searchForm->isSubmitted() && $searchForm->isValid()) {
			$data = $searchForm->getData();
			$queryBuilder = $em->getRepository('AppformFrontendBundle:Applicant')->getUsersPerFilter($data);

			//Generate reports
			if (isset($data['generate_report'])) {
				// Create new PHPExcel object
				$objPHPExcel = $this->get( 'phpexcel' )->createPHPExcelObject();
				// Set document properties
				$objPHPExcel->getProperties()->setCreator( "HealthcareTravelerNetwork" )
						->setLastModifiedBy( "HealthcareTravelerNetwork" )
						->setTitle( "Applicant Report" )
						->setSubject( "Applicant Document" );

				// get fields for select
				$fm = $this->container->get('hcen.fieldmanager');
				$fields = $fm->getUserReportFields();

				// Fill worksheet from values in array
				$objPHPExcel->getActiveSheet()->fromArray($fields, null, 'A1');
				$objPHPExcel->getActiveSheet()->fromArray($this->transformData($em->getRepository('AppformFrontendBundle:Applicant')->getUsersPerFilter($data, array_keys($fields))->getQuery()->getArrayResult()), null, 'A2');

				// Rename worksheet
				$objPHPExcel->getActiveSheet()->setTitle('Applicants');
				//Col width fix
				foreach (range('A', $objPHPExcel->getActiveSheet()->getHighestDataColumn()) as $col) {
					$objPHPExcel->getActiveSheet()
							->getColumnDimension($col)
							->setAutoSize(true);
				}
				$this->filename = 'applicants.';
				$this->filename .= $data['generate_report'] == 'CSV' ? 'csv' : 'xls';

				$objWriter = \PHPExcel_IOFactory::createWriter( $objPHPExcel, $data['generate_report'] );
				$objWriter->save($this->get('kernel')->getRootDir(). '/../web/reports/' . $this->filename);
				$this->get('session')->getFlashBag()->add('message', 'File has been generated');
			}
		} else {
			$queryBuilder = $em->getRepository('AppformFrontendBundle:Applicant')->getUsersPerFilter(false);
		}

		// Pagination
		$paginator = $this->get('knp_paginator');
		$pagination = null;
		$paginatorOptions = array(
				'defaultSortFieldName' => 'a.id',
				'defaultSortDirection' => 'desc');
		try {
			$pagination = $paginator->paginate(
					$queryBuilder,
					$this->get('request')->query->get('page', 1),
					isset($data['show_all']) && $data['show_all'] == 1 ? count($queryBuilder->getQuery()->getArrayResult()) : $this->get('request')->query->get('itemsPerPage', 20),
					$paginatorOptions
			);
		} catch (QueryException $ex) {
			$pagination = $paginator->paginate(
					$queryBuilder,
					1,
					isset($data['show_all']) && $data['show_all'] == 1 ? count($queryBuilder->getQuery()->getArrayResult()) : $this->get('request')->query->get('itemsPerPage', 20),
					$paginatorOptions
			);
		}
		// End Pagination

		return $this->render( 'AppformBackendBundle:User:index.html.twig', array(
				'pagination' => $pagination,
				'fileName' => $this->filename,
				'search_form' => $searchForm->createView(),
				'campaignForm' => $campaignForm->createView()));
	}

	protected function transformData ($data) {
		$helper = $this->get('helper');
		foreach ($data as $key => $value) {
			$data[$key]['desiredAssignementState'] = is_array($value['desiredAssignementState']) ? implode(', ', $value['desiredAssignementState']) : $value['desiredAssignementState'];
			$data[$key]['assignementTime'] = $helper->getAssTime($value['assignementTime']);
			$data[$key]['licenseState'] = is_array($value['licenseState']) ? implode(', ', $value['licenseState']) : $value['licenseState'];
			$data[$key]['discipline'] = $value['discipline'] || $value['discipline'] === '0' ? $helper->getDiscipline($value['discipline']) : '';
			$data[$key]['specialtyPrimary'] = $helper->getSpecialty($value['specialtyPrimary']);
			$data[$key]['specialtySecondary'] = $value['specialtySecondary'] ? $helper->getSpecialty($value['specialtySecondary']) : '';
			$data[$key]['yearsLicenceSp'] = $value['yearsLicenceSp'] || $value['yearsLicenceSp'] === '0' ? $helper->getExpYears($value['yearsLicenceSp']) : '';
			$data[$key]['yearsLicenceSs'] = $value['yearsLicenceSs'] || $value['yearsLicenceSs'] === '0' ? $helper->getExpYears($value['yearsLicenceSs']) : '';
			$data[$key]['isOnAssignement'] = $helper->getBoolean($value['isOnAssignement']);
			$data[$key]['isExperiencedTraveler'] = $helper->getBoolean($value['isExperiencedTraveler']);
			$data[$key]['path'] = $value['path'] ? "Yes" : 'No';
			$data[$key]['ip'] = $value['ip'] ? long2ip($value['ip']) : '';
		}
		return $data;
	}

	/**
	 * Creates a form to search an Order.
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createSearchForm()
	{
		$form = $this->createForm(
			new SearchType($this->container),
			null,
			array(
					'action' => $this->generateUrl('user_list'),
					'method' => 'GET',
			)
		);
		return $form;
	}

	/**
	 * Creates a form to search an Order.
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createCampaignForm()
	{
		$form = $this->createForm(
				new CampaignType(),
				null,
				array(
						'action' => $this->generateUrl('campaign_new'),
						'method' => 'GET',
				)
		);

		// Set defaults data to form
		$form->get('subject')->setData($this->get('hcen.settings')->getWebSite()->getSubject());
		$form->get('publishat')->setData(new \DateTime("now"));
		return $form;
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
	 * @Route("/download/{filename}", name="user_download", requirements={"filename" = ".*"})
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

	protected function sendReport(Applicant $applicant ) {
		$fm = $this->container->get('hcen.fieldmanager');

		$fields = $fm->getUserToXlsPdfFields();

		$data = $this->getDoctrine()->getManager()->getRepository('AppformFrontendBundle:Applicant')->getApplicantsData($applicant->getId(), array_keys($fields));
		$data = $fm->generateFormFields($data);

		// Create new PHPExcel object
		$objPHPExcel = $this->get( 'phpexcel' )->createPHPExcelObject();
		// Set document properties
		$objPHPExcel->getProperties()->setCreator( "HealthcareTravelerNetwork" )
				->setLastModifiedBy( "HealthcareTravelerNetwork" )
				->setTitle( "Applicant Data" )
				->setSubject( "Applicant Document" );
		// Fill worksheet from values in array
		$objPHPExcel->getActiveSheet()->fromArray($fields, null, 'A1');
		$objPHPExcel->getActiveSheet()->fromArray($data, null, 'A2');
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Applicant');
		//Col width fix
		foreach (range('A', $objPHPExcel->getActiveSheet()->getHighestDataColumn()) as $col) {
			$objPHPExcel->getActiveSheet()
					->getColumnDimension($col)
					->setAutoSize(true);
		}

		$objWriter = \PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel5' );
		$objWriter->save( $applicant->getDocument()->getUploadRootDir() . '/' . $applicant->getDocument()->getXls());

		try {
			$this->get( 'knp_snappy.pdf' )->generateFromHtml(
					$this->renderView(
							'AppformBackendBundle:Reports:pdf.html.twig',
							$data
					),$applicant->getDocument()->getUploadRootDir() . '/' . $applicant->getDocument()->getPdf());
		} catch (\Exception $e) {
			$this->get('session')->getFlashBag()->add('error', 'Pdf File exists in system');
		}

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