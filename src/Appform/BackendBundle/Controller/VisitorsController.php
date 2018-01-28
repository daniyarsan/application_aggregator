<?php

namespace Appform\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Users controller.
 *
 * @Route("/visitors")
 */
class VisitorsController extends Controller
{

	/**
	 * @Route("/", name="visitors")
	 * @Template()
	 */
	public function indexAction()
	{
		$visitorsRepo = $this->getDoctrine()->getRepository('AppformFrontendBundle:Visitor');
		$queryBuilder = $visitorsRepo->createQueryBuilder('v');
		$queryBuilder->orderBy('v.id', 'desc');

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

		$startDate = date( 'Y-m-' ) . '01'; // First day in current month
		$endDate   = date( 'Y-m-t' ); // Last day in current month

		$queryBuilder->where('v.lastActivity >= :first');
		$queryBuilder->andWhere('v.lastActivity <= :last')
			->setParameter('first', $startDate)
			->setParameter('last', $endDate);

		$query = $queryBuilder->getQuery();

		$query->setQueryCacheLifetime( 3600 );
		$query->setResultCacheLifetime( 3600 );
		$query->useQueryCache( true );

		return array(
			'pagination' => $pagination,
			'thisMonth' => count($query->getResult())
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


}
