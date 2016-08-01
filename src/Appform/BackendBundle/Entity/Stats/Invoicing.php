<?php

namespace Appform\BackendBundle\Entity\Stats;

use Doctrine\ORM\Mapping as ORM;

/**
 * AgencySenderStat
 *
 * 
 * @ORM\Entity(repositoryClass="Appform\BackendBundle\Entity\Stats\InvoicingRepository")
 */
class Invoicing
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer", name="id")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $agency_group;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $candidate_id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $discipline;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $specialty_primary;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $sent_date;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set agecnyGroup
     *
     * @param string $agecnyGroup
     *
     * @return Invoicing
     */
    public function setAgecnyGroup($agecnyGroup)
    {
        $this->agency_group = $agecnyGroup;

        return $this;
    }

    /**
     * Get agecnyGroup
     *
     * @return string
     */
    public function getAgecnyGroup()
    {
        return $this->agency_group;
    }

    /**
     * Set candidateId
     *
     * @param integer $candidateId
     *
     * @return Invoicing
     */
    public function setCandidateId($candidateId)
    {
        $this->candidate_id = $candidateId;

        return $this;
    }

    /**
     * Get candidateId
     *
     * @return integer
     */
    public function getCandidateId()
    {
        return $this->candidate_id;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Invoicing
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Invoicing
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set discipline
     *
     * @param string $discipline
     *
     * @return Invoicing
     */
    public function setDiscipline($discipline)
    {
        $this->discipline = $discipline;

        return $this;
    }

    /**
     * Get discipline
     *
     * @return string
     */
    public function getDiscipline()
    {
        return $this->discipline;
    }

    /**
     * Set specialtyPrimary
     *
     * @param string $specialtyPrimary
     *
     * @return Invoicing
     */
    public function setSpecialtyPrimary($specialtyPrimary)
    {
        $this->specialty_primary = $specialtyPrimary;

        return $this;
    }

    /**
     * Get specialtyPrimary
     *
     * @return string
     */
    public function getSpecialtyPrimary()
    {
        return $this->specialty_primary;
    }

    /**
     * Set sentDate
     *
     * @param \DateTime $sentDate
     *
     * @return Invoicing
     */
    public function setSentDate($sentDate)
    {
        $this->sent_date = $sentDate;

        return $this;
    }

    /**
     * Get sentDate
     *
     * @return \DateTime
     */
    public function getSentDate()
    {
        return $this->sent_date;
    }
}
