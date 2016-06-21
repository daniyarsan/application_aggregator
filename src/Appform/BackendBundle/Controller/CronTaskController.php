<?php

namespace Appform\BackendBundle\Controller;

use Appform\BackendBundle\Entity\CronTask;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/crontasks")
 */
class CronTaskController extends Controller {

	/**
	 * @Route("/", name="hcen_crontasks_index")
	 */
	public function indexAction()
	{
		return new Response('tetset');
	}

	/**
	 * @Route("/test", name="hcen_crontasks_test")
	 */
	public function testAction()
	{
		$entity = new CronTask();

		$entity
			->setName('Example asset symlinking task')
			->setInterval(3600) // Run once every hour
			->setCommands(array(
				'assets:install --symlink web'
			));

		$em = $this->getDoctrine()->getManager();
		$em->persist($entity);
		$em->flush();

		return new Response('OK!');
	}
}
