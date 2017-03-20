<?php

namespace Appform\FrontendBundle\Extensions;

use Appform\FrontendBundle\Entity\Counter as CountEntity;

class Counter {

	protected $container;
	public $secondsToConsiderOffline;
	protected $em;

	/**
	 * @param ContainerInterface $container
	 */
	public function __construct($container = null, $entityManager = null)
	{
		$this->container = $container;
		$this->em = $entityManager;
		$this->secondsToConsiderOffline = 20;
	}

	public function count() {
		$session = $this->container->get('session');
		$counterId = $session->get('counterId') ? $session->get('counterId') : uniqid();
		$session->set('counterId', $counterId);

		$counterRepository = $this->em->getRepository('AppformFrontendBundle:Counter');
		$time = new \DateTime();
		$gracePeriod = $time->setTimestamp(time() - $this->secondsToConsiderOffline);

		$query = $this->em->createQuery('DELETE FROM AppformFrontendBundle:Counter c WHERE c.last_activity < :gracePeriod');
		$query->setParameter('gracePeriod', $gracePeriod);
		$query->execute();

		if (!$counterRepository->userExists($counterId)) {
			$counter = new CountEntity();
			$counter->setSessionId($counterId);
			$counter->setTime(new \DateTime('now'));
			$this->em->persist($counter);
			$this->em->flush();
		}

		$counterindb = $counterRepository->findAll();

		return count($counterindb) < 5 ? 5 : count($counterindb);

//		Checker
//		$counterindb = $counterRepository->findAll();
//		var_dump($counterindb);
//		exit;
	}

}