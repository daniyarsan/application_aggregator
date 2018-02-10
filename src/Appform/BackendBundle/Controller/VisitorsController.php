<?php

namespace Appform\BackendBundle\Controller;

use Appform\BackendBundle\Form\SearchVisitorsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Users controller.
 *
 * @Route("/visitors")
 */
class VisitorsController extends Controller
{

	public $filename = false;

	/**
	 * @Route("/", name="visitors")
	 * @Template()
	 */
	public function indexAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$searchForm = $this->createSearchForm();
		$searchForm->handleRequest($request);

		if ($searchForm->isSubmitted() && $searchForm->isValid()) {
			$data = $searchForm->getData();
			$queryBuilder = $em->getRepository('AppformFrontendBundle:Visitor')->getUsersPerFilter($data);
			//Generate reports
			if (isset($data['generate_report'])) {
				// Create new PHPExcel object
				$objPHPExcel = $this->get( 'phpexcel' )->createPHPExcelObject();
				// Set document properties
				$objPHPExcel->getProperties()->setCreator( "HealthcareTravelerNetworkS" )
						->setLastModifiedBy( "HealthcareTravelerNetwork" )
						->setTitle( "Statistics Report" )
						->setSubject( "Statistics Document" );

				// get fields for select
				$fm = $this->container->get('hcen.fieldmanager');
				$fields = $fm->getStatsReportFields();

				// Fill worksheet from values in array
				$objPHPExcel->getActiveSheet()->fromArray($fields, null, 'A1');
				$objPHPExcel->getActiveSheet()->fromArray($em->getRepository('AppformFrontendBundle:Visitor')->getUsersPerFilter($data, array_keys($fields))->getQuery()->getArrayResult(), null, 'A2');

				// Rename worksheet
				$objPHPExcel->getActiveSheet()->setTitle('Visitors');
				//Col width fix
				foreach (range('A', $objPHPExcel->getActiveSheet()->getHighestDataColumn()) as $col) {
					$objPHPExcel->getActiveSheet()
							->getColumnDimension($col)
							->setAutoSize(true);
				}
				$this->filename = 'visitors.';
				$this->filename .= $data['generate_report'] == 'CSV' ? 'csv' : 'xls';

				$objWriter = \PHPExcel_IOFactory::createWriter( $objPHPExcel, $data['generate_report'] );
				$objWriter->save($this->get('kernel')->getRootDir(). '/../web/reports/' . $this->filename);
				$this->get('session')->getFlashBag()->add('message', 'Report File has been generated');
			}
		} else {
			$queryBuilder = $em->getRepository('AppformFrontendBundle:Visitor')->getUsersPerFilter(false);
		}

		$paginator = $this->get('knp_paginator');
		$pagination = null;
		try {
			$pagination = $paginator->paginate(
					$queryBuilder,
					$this->get('request')->query->get('page', 1),
					$this->get('request')->query->get('itemsPerPage', 20)
			);
		} catch (QueryException $ex) {
			$pagination = $paginator->paginate(
					$queryBuilder,
					1,
					$this->get('request')->query->get('itemsPerPage', 20)
			);
		}

		return array(
			'pagination' => $pagination,
			'fileName' => $this->filename,
			'search_form' => $searchForm->createView(),
		);
	}

	/**
	 * @Route("/show")
	 * @Template()
	 */
	public function showAction()
	{
		return array(
			// ...
		);
	}

	/**
	 * @Route("/generate-location")
	 * @Template()
	 */
	public function generateAction()
	{
		$em = $this->getDoctrine()->getManager();
		$visitors = $em->getRepository('AppformFrontendBundle:Visitor')->findAll();

		foreach ($visitors as $visitor) {
			if ($visitor->getCountry() == false) {
				$response = file_get_contents('http://freegeoip.net/json/'.$visitor->getIp());
				$data = json_decode($response, true);
				$visitor->setCountry($data['country_name']);
				$visitor->setState($data['region_name']);
				$visitor->setCity($data['city']);
				$visitor->setZipcode($data['zip_code']);
				$em->persist( $visitor );
				$em->flush();
			}
		}
		return $this->redirect($this->generateUrl('visitors'));
	}

	/**
	 * Creates a form to search an Order.
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createSearchForm()
	{
		$form = $this->createForm(
				new SearchVisitorsType($this->container),
				null,
				array(
					'action' => $this->generateUrl('visitors'),
					'method' => 'GET',
				)
		);
		return $form;
	}


}
