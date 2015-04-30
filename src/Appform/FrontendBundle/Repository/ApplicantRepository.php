<?php

namespace Appform\FrontendBundle\Repository;


use Doctrine\ORM\EntityRepository;

class ApplicantRepository extends EntityRepository
{
    public function getLastMonth(){
        $now = new \DateTime();
        $thirtyDaysAgo = $now->sub(new \DateInterval("P30D"));

        $qb = $this->createQueryBuilder('a');
        $qb
            ->where($qb->expr()->between('a.created', ':start', ':end'))
            ->setParameters(array('start' => $thirtyDaysAgo, 'end' => new \DateTime()));

        return $qb->getQuery()->getResult();
    }
}