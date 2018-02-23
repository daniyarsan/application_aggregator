<?php

namespace Appform\FrontendBundle\Entity;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * VisitorRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VisitorRepository extends \Doctrine\ORM\EntityRepository
{
	public function saveUniqueVisitor($ip, $referrer, $refUrl)
	{
		$lastActivity = new \DateTime('now');
		$em = $this->getEntityManager();

		if (!$this->hasVisitor($ip, $refUrl)) {
			$visitor = new Visitor();
			$visitor->setIp($ip);
			$visitor->setLastActivity($lastActivity);
			$visitor->setReferrer($referrer);
			$visitor->setRefUrl($refUrl);

			$em->persist($visitor);
			$em->flush();
		}
	}

	protected function hasVisitor($ip, $refUrl) {

		$time = new \DateTime('now');
		$time->modify('-1 day');

		return $this->createQueryBuilder('v')
				->select( 'count(v)' )
				->where('v.ip = :ip and v.refUrl = :refUrl')
				->andWhere('v.lastActivity > :time')
				->setParameter('ip', $ip)
				->setParameter('time', $time)
				->setParameter('refUrl', $refUrl)
				->getQuery()
				->getSingleScalarResult();
	}

	public function getRecentVisitor($ip) {
		$time = new \DateTime('now');
		$time->modify('-1 day');

		return $this->createQueryBuilder('v')
				->where('v.ip = :ip')
				->andWhere('v.lastActivity > :time')
				->setParameter('ip', $ip)
				->setMaxResults(1)
				->setParameter('time', $time)
				->getQuery()
				->getOneOrNullResult();
	}

	public function getAvailableReferers()
	{
		$qb = $this->createQueryBuilder('v')
				->select( 'v.referrer' )
				->distinct();
		return $qb->getQuery()->getResult();
	}

	public function getUsersPerFilter($criteria, $selectFields = false) {

		$qb = $this->createQueryBuilder('v');

		if (!empty($selectFields)) {
			$qb->select($selectFields);
		}

		if ($criteria['referrers'] != '') {
			$qb->where('v.referrer = :referer')->setParameter('referer', $criteria['referrers']);
		}

		if (!empty($criteria['fromdate'])) {
			$qb->andWhere('v.lastActivity >= :fromdate')
			->setParameter('fromdate', $criteria['fromdate']);
		}
		if (!empty($criteria['todate'])) {
			$qb->andWhere('v.lastActivity <= :todate')
			->setParameter('todate', $criteria['todate']);
		}

		$qb->orderBy('v.id', 'desc');

		return $qb;
	}

	public function countAllApplied()
	{
		return $this->createQueryBuilder('v')
				->select( 'count(v)' )
				->where('v.user_id is NOT NULL')
				->getQuery()
				->getSingleScalarResult();
	}
	public function countThisMonthApplied()
	{
		$time = new \DateTime('now');
		$time->modify('-30 day');

		return $this->createQueryBuilder('v')
				->select( 'count(v)' )
				->where('v.user_id is NOT NULL')
				->andWhere('v.lastActivity > :time')
				->setParameter('time', $time)
				->getQuery()
				->getSingleScalarResult();

	}

	public function countThisMonthVisitors()
	{
		$time = new \DateTime('now');
		$time->modify('-30 day');

		return $this->createQueryBuilder('v')
				->select( 'count(v)' )
				->andWhere('v.lastActivity > :time')
				->setParameter('time', $time)
				->getQuery()
				->getSingleScalarResult();

	}

	public function getVisitorsAppliesPerReferrer()
	{
		return $this->createQueryBuilder('v')
				->select( 'v.referrer as referrer', 'count(v) as cnt' )
				->where('v.user_id is NOT NULL')
				->groupBy('v.referrer')
				->getQuery()
				->getResult();
	}

	public function getVisitorsTotalPerReferrer()
	{
		return $this->createQueryBuilder('v')
				->select( 'v.referrer as referrer', 'count(v) as cnt' )
				->groupBy('v.referrer')
				->getQuery()
				->getResult();
	}
}
