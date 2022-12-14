<?php

namespace Appform\FrontendBundle\Repository;


use Doctrine\ORM\EntityRepository;

class ApplicantRepository extends EntityRepository
{

    /**
     * Filter for main Users search
     * @param $criteria
     * @return \Doctrine\ORM\QueryBuilder
     *
     */
    public function getUsersPerFilter($criteria, $selectFields = false)
    {

        $qb = $this->createQueryBuilder('a');
        $qb->leftJoin('a.personalInformation', 'p');
        $qb->leftJoin('a.document', 'd');
        if (!empty($selectFields)) {
            $qb->select($selectFields);
        }

        if (!empty($criteria[ 'state' ])) {
            $qb->where("p.state = '" . $criteria[ 'state' ] . "'");
        }
        if (!empty($criteria[ 'discipline' ]) && is_array($criteria[ 'discipline' ])) {
            $qb->andWhere($qb->expr()->in('p.discipline', $criteria[ 'discipline' ]));
        }
        if (!empty($criteria[ 'specialtyPrimary' ]) && is_array($criteria[ 'specialtyPrimary' ])) {
            $qb->andWhere('p.specialtyPrimary IN (:specPrimary)')
                ->setParameter('specPrimary', $criteria[ 'specialtyPrimary' ]);
        }
        if (!empty($criteria[ 'isExperiencedTraveler' ]) || $criteria[ 'isExperiencedTraveler' ] == '0') {
            $qb->andWhere('p.isExperiencedTraveler = ' . $criteria[ 'isExperiencedTraveler' ]);
        }
        if (!empty($criteria[ 'isOnAssignement' ]) || $criteria[ 'isOnAssignement' ] == '0') {
            $qb->andWhere('p.isOnAssignement = ' . $criteria[ 'isOnAssignement' ]);
        }
        if (!empty($criteria[ 'candidateId' ])) {
            $qb->andWhere('a.candidateId = ' . $criteria[ 'candidateId' ]);
        }
        if (!empty($criteria[ 'name' ])) {
            $nameRequest = explode(' ', $criteria[ 'name' ]);
            if (isset($nameRequest[ 0 ])) {
                $qb->andWhere($qb->expr()->like('a.firstName', ':name'))->setParameter('name', '%' . $nameRequest[ 0 ] . '%');
            }
            if (isset($nameRequest[ 1 ])) {
                $qb->andWhere($qb->expr()->like('a.lastName', ':surname'))->setParameter('surname', '%' . $nameRequest[ 1 ] . '%');
            }
        }
        if (!empty($criteria[ 'hasResume' ])) {
            if ($criteria[ 'hasResume' ] == '1') {
                $qb->andWhere('d.path IS NOT NULL');
            }
        }
        if (!empty($criteria[ 'referrers' ])) {
            $qb->andWhere('a.appReferer IN (:referers)')->setParameter('referers', $criteria[ 'referrers' ]);
        }
        if (!empty($criteria[ 'fromdate' ])) {
            $qb->andWhere('a.created >= :fromdate')
                ->setParameter('fromdate', $criteria[ 'fromdate' ]);
        }
        if (!empty($criteria[ 'todate' ])) {
            $qb->andWhere('a.created <= :todate')
                ->setParameter('todate', $criteria[ 'todate' ]);
        }
        return $qb;
    }

    /**
     * Todays applications Widget
     * @return mixed
     */
    public function getPostsByDay()
    {
        $time = new \DateTime();
        $time->modify('today');

        $totime = clone $time;
        $totime->modify("tomorrow");
        $totime->modify('1 second ago');

        $qb = $this->createQueryBuilder('applicant')
            ->select('count(applicant)')
            ->where('applicant.created BETWEEN :start AND :end')
            ->setParameter('start', $time)
            ->setParameter('end', $totime);

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     *
     * @return array
     */
    public function getLastMonth()
    {
        $now = new \DateTime();
        $thirtyDaysAgo = $now->sub(new \DateInterval("P30D"));

        $qb = $this->createQueryBuilder('a');
        $qb->where($qb->expr()->between('a.created', ':start', ':end'))
            ->setParameters(array('start' => $thirtyDaysAgo, 'end' => new \DateTime()));

        return $qb->getQuery()->getResult();
    }

    /**
     * By month Widget
     * @param $year
     * @param $month
     * @return mixed
     */

    public function getPostsByMonth($year, $month)
    {
        $date = new \DateTime("{$year}-{$month}-01");
        $toDate = clone $date;
        $toDate->modify("next month midnight -1 second");

        $qb = $this->createQueryBuilder('applicant')
            ->select('count(applicant)')
            ->where('applicant.created BETWEEN :start AND :end')
            ->setParameter('start', $date)
            ->setParameter('end', $toDate);

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Get array of Referrers for drop down field in Searchtype.php
     * @return array
     */
    public function getAvailableReferers()
    {
        $qb = $this->createQueryBuilder('b')
            ->select('b.appReferer')
            ->distinct();
        return $qb->getQuery()->getResult();
    }

    /**
     * Counts all applicants for Widget on dashboard
     * @return mixed
     */
    public function getCountAllApplicants()
    {
        return $this->createQueryBuilder('a')
            ->select('count(a)')
            ->getQuery()->getSingleScalarResult();
    }

    /**
     * Get data of certain fields of Applicant per id
     * @param $id
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getApplicantsData($id, $fields = false)
    {
        if (!$fields) {
            $fields = ['a.id',
                'a.candidateId',
                'a.firstName',
                'a.lastName',
                'a.email',
                'a.created',
                'a.appReferer',
                'a.ip',
                'p.phone',
                'p.state',
                'p.discipline',
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
        }

        return $this->createQueryBuilder('a')
            ->leftJoin('a.personalInformation', 'p')
            ->leftJoin('a.document', 'd')
            ->select($fields)
            ->where('a.id = :id')
            ->setParameter('id', $id)
            ->getQuery()->getOneOrNullResult();
    }

    public function getApplicantPerToken($token)
    {
        $qb = $this->createQueryBuilder('a');
        return $qb->select(['a.id', 'p.discipline'])
            ->leftJoin('a.personalInformation', 'p')
            ->where('a.token = :token')->setParameter('token', $token)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByIpCheck($ip)
    {
        $qb = $this->createQueryBuilder('a');
        return $qb->where('a.ip = :ip')->setParameter('ip', ip2long($ip))
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
    }
}