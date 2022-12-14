<?php

namespace Appform\FrontendBundle\Entity;

/**
 * CounterRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CounterRepository extends \Doctrine\ORM\EntityRepository
{
	public function userExists($sessionId) {
		return (boolean)$this->createQueryBuilder('c')
			->andWhere('c.session_id = :sessionId')
			->setParameter('sessionId', $sessionId)
			->getQuery()
			->getOneOrNullResult();
	}
}
