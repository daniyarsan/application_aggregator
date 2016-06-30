<?php

namespace Appform\BackendBundle\Helper;

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

	public function generateFormFields(array $applicant)
	{
		$helper = $this->container->get('helper');
		$applicant['state'] = $helper->getStates($applicant['state']);
		$applicant['discipline'] = $helper->getDiscipline($applicant['discipline']);
		$applicant['specialtyPrimary'] = $helper->getSpecialty($applicant['specialtyPrimary']);
		$applicant['specialtySecondary'] = $applicant['specialtySecondary'] ? $helper->getSpecialty($applicant['specialtySecondary']) : false;
		$applicant['yearsLicenceSp'] = $helper->getExpYears($applicant['yearsLicenceSp']);
		$applicant['yearsLicenceSs'] = $applicant['yearsLicenceSs'] ? $helper->getExpYears($applicant['yearsLicenceSs']) : false;
		$applicant['licenseState'] = implode(', ', $applicant['licenseState']);
		$applicant['desiredAssignementState'] = implode(', ', $applicant['desiredAssignementState']);
		$applicant['isExperiencedTraveler'] = $applicant['isExperiencedTraveler'] == true ? 'Yes' : 'No';
		$applicant['assignementTime'] = $helper->getAssTime($applicant['assignementTime']);

		return $applicant;
	}

	public function test()
	{
		$em = $this->container->get('doctrine.orm.entity_manager');
		$applicantFields = $em->getClassMetadata('AppformFrontendBundle:Applicant')->getFieldNames();
		$personalInfoFields = $em->getClassMetadata('AppformFrontendBundle:PersonalInformation')->getFieldNames();

		return array_merge( $applicantFields, $personalInfoFields );
	}
}