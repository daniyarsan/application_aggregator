<?php

namespace Appform\BackendBundle\Entity\Stats;
use Appform\BackendBundle\Entity\Campaign;

/**
 * InvoicingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InvoicingRepository extends \Doctrine\ORM\EntityRepository
{
	public function saveInvoicingStats(Campaign $campaign)
	{
		$invoicing = new Invoicing();
		$invoicing->setAgecnyGroup($campaign->getAgencygroup()->getName());
		$em = $this->getEntityManager();
		$applicant = $em->getRepository('AppformFrontendBundle:Applicant')->find($campaign->getApplicant());
		$invoicing->setCandidateId($applicant->getCandidateId());
		$invoicing->setDiscipline($applicant->getPersonalInformation()->getDiscipline());
		$invoicing->setFirstName($applicant->getFirstName());
		$invoicing->setLastName($applicant->getLastName());
		$invoicing->setSpecialtyPrimary($applicant->getPersonalInformation()->getSpecialtyPrimary());
		$invoicing->setSentDate($campaign->getPublishdate());
		$em->persist($invoicing);
		$em->flush();
	}
}
