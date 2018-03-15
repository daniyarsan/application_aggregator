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
		$applicant['created'] = $applicant['created']->format('m/d/Y - H:i');
		$applicant['state'] = $helper->getStates($applicant['state']);
		$applicant['discipline'] = $helper->getDisciplineShort($applicant['discipline']);
		$applicant['specialtyPrimary'] = $helper->getSpecialtyShort($applicant['specialtyPrimary']);
		$applicant['specialtySecondary'] = $applicant['specialtySecondary'] ? $helper->getSpecialtyShort($applicant['specialtySecondary']) : false;
		$applicant['yearsLicenceSp'] = $helper->getExpYears($applicant['yearsLicenceSp']);
		$applicant['yearsLicenceSs'] = $applicant['yearsLicenceSs'] ? $helper->getExpYears($applicant['yearsLicenceSs']) : false;
		$applicant['licenseState'] = implode(', ', $applicant['licenseState']);
		$applicant['desiredAssignementState'] = implode(', ', $applicant['desiredAssignementState']);
		$applicant['isExperiencedTraveler'] = $applicant['isExperiencedTraveler'] == true ? 'Yes' : 'No';
		$applicant['assignementTime'] = $helper->getAssTime($applicant['assignementTime']);

		return $applicant;
	}

	/**
	 * return array of Fields for Select request in Doctrine
	 * @return array
	 */
	public function getAllFields()
	{
		$em = $this->container->get('doctrine.orm.entity_manager');
		$applicantFields = $em->getClassMetadata('AppformFrontendBundle:Applicant')->getFieldNames();
		array_walk($applicantFields, function (&$value, $key) { $value = 'a.' . $value; });
		$personalInfoFields = $em->getClassMetadata('AppformFrontendBundle:PersonalInformation')->getFieldNames();
		array_walk($personalInfoFields, function (&$value, $key) { $value = 'p.' . $value; });
		$documentfields = $em->getClassMetadata('AppformFrontendBundle:Document')->getFieldNames();
		array_walk($documentfields, function (&$value, $key) { $value = 'd.' . $value; });

		return array_merge( $applicantFields, $personalInfoFields, $documentfields );
	}

	public function getUserReportFields () {
		return ['a.id' => 'Id',
				'a.candidateId' => 'Candidate Id',
				'a.firstName' => 'First Name',
				'a.lastName' => 'Last Name',
				'a.email' => 'Email',
				'a.ip' => 'IP',
				'a.appReferer' => 'Referer',
				'a.refUrl' => 'Referrer Url',
				'a.created' => 'Date Applied',
				'p.phone' => 'Phone',
				'p.state' => 'State',
				'p.discipline' => 'Discipline',
				'p.licenseState' => 'License State',
				'p.specialtyPrimary' => 'Primary Specialty',
				'p.yearsLicenceSp' => 'Primary Specialty Experience',
				'p.specialtySecondary' => 'Secondary Specialty',
				'p.yearsLicenceSs' => 'Secondary Specialty Experience',
				'p.desiredAssignementState' => 'Desired Assignment State',
				'p.isExperiencedTraveler' => 'Have Experience',
				'p.assignementTime' => 'Desired Assignment Time',
				'p.isOnAssignement' => 'Is on assignment',
				'p.completion' => 'Completion Date',
				'd.path' => 'Document'];
	}

	public function getUserToXlsPdfFields () {
		return ['a.id' => 'Id',
				'a.candidateId' => 'Candidate Id',
				'a.firstName' => 'First Name',
				'a.lastName' => 'Last Name',
				'a.email' => 'Email',
				'a.created' => 'Date Applied',
				'p.phone' => 'Phone',
				'p.state' => 'State',
				'p.discipline' => 'Discipline',
				'p.licenseState' => 'License State',
				'p.specialtyPrimary' => 'Primary Specialty',
				'p.yearsLicenceSp' => 'Primary Specialty Experience',
				'p.specialtySecondary' => 'Secondary Specialty',
				'p.yearsLicenceSs' => 'Secondary Specialty Experience',
				'p.desiredAssignementState' => 'Desired Assignment State',
				'p.isExperiencedTraveler' => 'Have Experience',
				'p.assignementTime' => 'Desired Assignment Time',
				'p.isOnAssignement' => 'Is on assignment',
				'p.completion' => 'Completion Date',
				'd.path' => 'Document'];
	}

	public function getStatsReportFields () {
		return ['v.id' => 'Id',
				'v.ip' => 'IP',
				'v.referrer' => 'Referrer',
				'v.refUrl' => 'Referrer URL',
				'v.lastActivity' => 'Referrer'];
	}

}