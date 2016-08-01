<?php

namespace Appform\BackendBundle\Controller\Stats;

use Appform\BackendBundle\Form\InvoicingSearchType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Appform\BackendBundle\Entity\Stats\Invoicing;
use Symfony\Component\HttpFoundation\Request;

/**
 * Stats\Invoicing controller.
 *
 * @Route("/invoicing")
 */
class InvoicingController extends Controller
{

	public $pathToReport = false;

	/**
	 * Lists all Stats\Invoicing entities.
	 *
	 * @Route("/", name="stats_invoicing")
	 * @Method("GET")
	 * @Template()
	 */
	public function indexAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();

		$queryBuilder = $em->getRepository('AppformBackendBundle:Stats\Invoicing')->createQueryBuilder('i');

		$searchForm = $this->createSearchForm();
		$searchForm->handleRequest($request);

		if ($searchForm->isSubmitted()) {
			$data = $searchForm->getData();

			if (!empty($data[ 'id' ])) {
				$queryBuilder->where('i.id = :id')
					->setParameter('id', $data[ 'id' ]);
			}
			if (!empty($data[ 'agency_group' ])) {
				$queryBuilder->andWhere('i.agency_group = :agency_group')
						->setParameter('agency_group', $data[ 'agency_group' ]);
			}
			if (!empty($data[ 'candidate_id' ])) {
				$queryBuilder->andWhere('i.candidate_id = :candidate_id')
						->setParameter('candidate_id', $data[ 'candidate_id' ]);
			}
			if (!empty($data[ 'discipline' ])) {
				$queryBuilder->andWhere('i.discipline = :discipline')
						->setParameter('discipline', $data[ 'discipline' ]);
			}
			if (!empty($data[ 'specialty_primary' ])) {
				$queryBuilder->andWhere('i.specialty_primary = :specialty_primary')
						->setParameter('specialty_primary', $data[ 'specialty_primary' ]);
			}
			if (!empty($data[ 'specialty_primary' ])) {
				$queryBuilder->andWhere('i.specialty_primary = :specialty_primary')
						->setParameter('specialty_primary', $data[ 'specialty_primary' ]);
			}
			if (!empty($data['fromdate'])) {
				$queryBuilder->andWhere('i.sent_date >= :fromdate')
						->setParameter('fromdate', $data['fromdate']);
			}

			if (!empty($data['todate'])) {
				$queryBuilder->andWhere('i.sent_date <= :todate')
						->setParameter('todate', $data['todate']);
			}

			if ($data['generate_report']) {
				// Create new PHPExcel object
				$objPHPExcel = $this->get( 'phpexcel' )->createPHPExcelObject();
				// Set document properties
				$objPHPExcel->getProperties()->setCreator( "HealthcareTravelerNetwork" )
						->setLastModifiedBy( "HealthcareTravelerNetwork" )
						->setTitle( "Agency Invoicing Report" )
						->setSubject( "Agency Invoicing Report" );

				$fieldNames = $em->getClassMetadata('AppformBackendBundle:Stats\Invoicing')->getFieldNames();
				// Fill worksheet from values in array
				$objPHPExcel->getActiveSheet()->fromArray($fieldNames, null, 'A1');
				$objPHPExcel->getActiveSheet()->fromArray($queryBuilder->getQuery()->getArrayResult(), null, 'A2');

				// Rename worksheet
				$objPHPExcel->getActiveSheet()->setTitle('Invoicing');
				//Col width fix
				foreach (range('A', $objPHPExcel->getActiveSheet()->getHighestDataColumn()) as $col) {
					$objPHPExcel->getActiveSheet()
							->getColumnDimension($col)
							->setAutoSize(true);
				}
				$objWriter = \PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel5' );
				$this->pathToReport = $this->get('kernel')->getRootDir(). '/../web/reports/invoicing.xls';
				$objWriter->save($this->pathToReport);
			}
		}

		$paginator = $this->get('knp_paginator');
		$pagination = null;
		$paginatorOptions = array(
			'defaultSortFieldName' => 'i.id',
			'defaultSortDirection' => 'desc');

		try {
			$pagination = $paginator->paginate(
				$queryBuilder,
				$this->get('request')->query->get('page', 1),
				$this->get('request')->query->get('itemsPerPage', 20),
				$paginatorOptions
			);
		} catch (QueryException $ex) {
			$pagination = $paginator->paginate(
				$queryBuilder,
				1,
				$this->get('request')->query->get('itemsPerPage', 20),
				$paginatorOptions
			);
		}

		return array(
			'entities' => $queryBuilder,
			'search_form' => $searchForm->createView(),
			'pagination' => $pagination,
			'pathToReport' => $this->pathToReport,
		);
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

	/**
	 * Finds and displays a Stats\Invoicing entity.
	 *
	 * @Route("/{id}", name="stats_invoicing_show")
	 * @Method("GET")
	 * @Template()
	 */
	public function showAction($id)
	{
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('AppformBackendBundle:Stats\Invoicing')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Stats\Invoicing entity.');
		}

		return array(
			'entity' => $entity,
		);
	}

	/**
	 * Creates a form to search an Order.
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createSearchForm()
	{
		$form = $this->createForm(
			new InvoicingSearchType(),
			null,
			array(
				'action' => $this->generateUrl('stats_invoicing'),
				'method' => 'GET',
			)
		);
		return $form;
	}
}
