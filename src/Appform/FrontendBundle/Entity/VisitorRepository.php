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

			$details = file_get_contents('http://freegeoip.net/json/'.$ip);
			if (!empty($details)) {
				$ipDetails = json_decode($details, true);
				$visitor->setCountry($ipDetails['country_name']);
				$visitor->setState($ipDetails['region_name']);
				$visitor->setCity($ipDetails['city']);
				$visitor->setZipcode($ipDetails['zip_code']);
			}

			$em->persist($visitor);
			$em->flush();
		}
	}

	protected function hasVisitor($ip, $refUrl) {

		$time = new \DateTime('now');
		$time->modify('-5 minutes');

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

	/**
	 * Get array of Referrers for drop down field in Searchtype.php
	 * @return array
	 */
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

	public function getVisitorsWithoutLocation()
	{
		return $this->createQueryBuilder('v')
			->where("v.country = ''")
			->setMaxResults(5000)
			->getQuery()
			->getResult();
	}
}
