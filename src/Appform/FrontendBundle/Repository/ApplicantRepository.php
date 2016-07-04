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

	public function getUsersPerFilter($criteria = array(), $sort, $direction) {
		$qb = $this->createQueryBuilder('a');
		$qb->leftJoin('a.personalInformation', 'p');
		$qb->leftJoin('a.document', 'd');

		if (!empty($criteria['state'])) {
			$qb->where("p.state = '".$criteria['state']."'");
		}

		if (isset($criteria['discipline']) && is_array($criteria['discipline'])) {
			$qb->andWhere('p.discipline IN (:disciplines)');
			$qb->setParameter('disciplines', array_values($criteria['discipline']));
		}
		if (isset($criteria['specialtyPrimary']) && is_array($criteria['specialtyPrimary'])) {
			$qb->andWhere('p.specialtyPrimary IN (:specPrimary)');
			$qb->setParameter('specPrimary', array_values($criteria['specialtyPrimary']));
		}

		if (!empty($criteria['isExperiencedTraveler']) || $criteria['isExperiencedTraveler'] == '0') {
			$qb->andWhere('p.isExperiencedTraveler = '.$criteria['isExperiencedTraveler']);
		}
		if (!empty($criteria['isOnAssignement']) || $criteria['isOnAssignement'] == '0') {
			$qb->andWhere('p.isOnAssignement = '.$criteria['isOnAssignement']);
		}
		if (isset($criteria['hasResume'])) {
			if ($criteria['hasResume'] == '1') {
				$qb->andWhere('d.path IS NOT NULL');
			}
		}

		if ($criteria['referrers'] != '') {
			$qb->andWhere('a.appReferer = :referer')->setParameter('referer', $criteria['referrers']);
		}

		if (!empty($criteria['range'])) {
			$period = explode(' - ',$criteria['range']);
			$qb->andWhere('a.created between :from and :to');
			$qb->setParameter('from', new \DateTime($period[0]));
			$qb->setParameter('to', new \DateTime($period[1]));
		}

		$qb->orderBy('a.'.$sort, $direction);
		return $qb->getQuery()->getResult();
	}

	public function getUsers($sort, $direction) {
		$qb = $this->createQueryBuilder('a');
		$qb->leftJoin('a.personalInformation', 'p');
		$qb->leftJoin('a.document', 'd');


		$qb->orderBy('a.'.$sort, $direction);
		return $qb->getQuery()->getResult();
	}

	public function findApplicantById( $id ) {
		return $this->findById($id);
	}

	public function countReferers() {
		return $this->createQueryBuilder( 's' )
		            ->select( 's.appReferer' )
		            ->addSelect( 'count(s.appReferer)' )
		            ->groupBy( 's.appReferer' )
		            ->getQuery()
		            ->getResult();
	}

	public function getPostsByDay()
	{
		$time = new \DateTime();
		$time->modify('today');

		$totime = clone $time;
		$totime->modify("tomorrow");
		$totime->modify('1 second ago');

		$qb = $this->createQueryBuilder('applicant')
		           ->select( 'count(applicant)' )
		           ->where('applicant.created BETWEEN :start AND :end')
		           ->setParameter('start', $time)
		           ->setParameter('end', $totime);

		return $qb->getQuery()->getSingleScalarResult();
	}

	public function getPostsByMonth($year, $month)
	{
		$date = new \DateTime("{$year}-{$month}-01");
		$toDate = clone $date;
		$toDate->modify("next month midnight -1 second");

		$qb = $this->createQueryBuilder('applicant')
		           ->select( 'count(applicant)' )
		           ->where('applicant.created BETWEEN :start AND :end')
		           ->setParameter('start', $date)
		           ->setParameter('end', $toDate);

		return $qb->getQuery()->getSingleScalarResult();
	}

	public function getAvailableReferers()
	{
		$qb = $this->createQueryBuilder('b')
		           ->select( 'b.appReferer' )
		           ->distinct();
		return $qb->getQuery()->getResult();
	}

	public function getCountAllApplicants() {
		return $this->createQueryBuilder('a')
					->select('count(a)')
					->getQuery()->getSingleScalarResult();
	}

	public function getApplicantsData($id) {
		$params = ['a.id',
				'a.candidateId',
				'a.firstName',
				'a.lastName',
				'a.email',
				'p.phone',
				'p.state',
				'p.discipline',
				'p.licenseState',
				'p.licenseState',
				'p.specialtyPrimary',
				'p.yearsLicenceSp',
				'p.specialtySecondary',
				'p.yearsLicenceSs',
				'p.desiredAssignementState',
				'p.isExperiencedTraveler',
				'p.assignementTime',
				'p.isOnAssignement',
				'p.completion',
				'd.path',
				'd.pdf',
				'd.xls'];
		return $this->createQueryBuilder('a')
				->leftJoin('a.personalInformation', 'p')
				->leftJoin('a.document', 'd')
				->select($params)
				->where('a.id = :id')
				->setParameter('id', $id)
				->getQuery()->getOneOrNullResult();
	}
}