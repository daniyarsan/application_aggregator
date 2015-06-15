<?php

namespace Appform\FrontendBundle\Repository;


use Doctrine\ORM\EntityRepository;

class ApplicantRepository extends EntityRepository {

	public function getLastMonth() {
		$now           = new \DateTime();
		$thirtyDaysAgo = $now->sub( new \DateInterval( "P30D" ) );

		$qb = $this->createQueryBuilder( 'a' );
		$qb->where( $qb->expr()->between( 'a.created', ':start', ':end' ) )
			->setParameters( array( 'start' => $thirtyDaysAgo, 'end' => new \DateTime() ) );

		return $qb->getQuery()->getResult();
	}

	public function getOrderByDirection($criteria = array(), $sort, $direction) {
		$qb = $this->createQueryBuilder('a');
		$qb->leftJoin('a.personalInformation', 'p');

		if (!empty($criteria['discipline']) || $criteria['discipline'] == '0') {
			$qb->where('p.discipline = '.$criteria['discipline']);
		}
		if (!empty($criteria['state'])) {
			$qb->andWhere("p.state = '".$criteria['state']."'");
		}
		if (!empty($criteria['specialtyPrimary']) || $criteria['specialtyPrimary'] == '0') {
			$qb->andWhere('p.specialtyPrimary = '.$criteria['specialtyPrimary']);
		}
		if (!empty($criteria['isExperiencedTraveler']) || $criteria['isExperiencedTraveler'] == '0') {
			$qb->andWhere('p.isExperiencedTraveler = '.$criteria['isExperiencedTraveler']);
		}
		if (!empty($criteria['range'])) {
			$period = explode(' - ',$criteria['range']);
			$qb->andWhere('a.created >= :from');
			$qb->andWhere('a.created <= :to');
			$qb->setParameter('from', new \DateTime($period[0]));
			$qb->setParameter('to', new \DateTime($period[1]));
		}

		$qb->orderBy('a.'.$sort, $direction);
		return $qb->getQuery()->getResult();
	}

	public function findApplicantById( $id ) {
		return $this->findById($id);
	}
}