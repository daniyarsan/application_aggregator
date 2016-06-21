<?php
/**
 * Created by PhpStorm.
 * User: daniyar
 * Date: 21.06.16
 * Time: 15:21
 */

namespace Appform\BackendBundle\Helper;

use Appform\FrontendBundle\Form\ApplicantType;
use Appform\FrontendBundle\Form\PersonalInformationType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FieldManager
{
	/**
	 * @var ContainerInterface
	 */
	private $container;

	/**
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function generateFormFields()
	{
		$em = $this->container->get('doctrine.orm.entity_manager');
		$applicantFields = $em->getClassMetadata('AppformFrontendBundle:Applicant')->getFieldNames();
		$personalInfoFields = $em->getClassMetadata('AppformFrontendBundle:PersonalInformation')->getFieldNames();

		return array_merge( $applicantFields, $personalInfoFields );
	}
}