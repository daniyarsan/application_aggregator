<?php

namespace Appform\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdditionalInformation
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class AdditionalInformation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var string
     *
     * @ORM\Column(name="hasLicense", type="integer", length=2)
     */
    private $hasLicense;

    /**
     * @var string
     *
     * @ORM\Column(name="hasLicenseExplanation", type="text")
     */
    private $hasLicenseExplanation;

    /**
     * @var string
     *
     * @ORM\Column(name="felony", type="integer", length=2)
     */
    private $felony;

    /**
     * @var string
     *
     * @ORM\Column(name="felonyExplanation", type="text")
     */
    private $felonyExplanation;

    /**
     * @var string
     *
     * @ORM\Column(name="proofEligibility", type="integer", length=2)
     */
    private $proofEligibility;

    /**
     * @var string
     *
     * @ORM\Column(name="citezenship", type="integer", length=2)
     */
    private $citezenship;

    /**
     * @var string
     *
     * @ORM\Column(name="visaType", type="string", length=255)
     */
    private $visaType;

    /**
     * @var string
     *
     * @ORM\Column(name="certification", type="string", length=255)
     */
    private $certification;

    /**
     * @ORM\OneToOne(targetEntity="Applicant")
     * @ORM\JoinColumn(name="applicant_id", referencedColumnName="id")
     **/
    private $applicant;

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
     * Set hasLicense
     *
     * @param integer $hasLicense
     * @return AdditionalInformation
     */
    public function setHasLicense($hasLicense)
    {
        $this->hasLicense = $hasLicense;

        return $this;
    }

    /**
     * Get hasLicense
     *
     * @return integer 
     */
    public function getHasLicense()
    {
        return $this->hasLicense;
    }

    /**
     * Set hasLicenseExplanation
     *
     * @param string $hasLicenseExplanation
     * @return AdditionalInformation
     */
    public function setHasLicenseExplanation($hasLicenseExplanation)
    {
        $this->hasLicenseExplanation = $hasLicenseExplanation;

        return $this;
    }

    /**
     * Get hasLicenseExplanation
     *
     * @return string 
     */
    public function getHasLicenseExplanation()
    {
        return $this->hasLicenseExplanation;
    }

    /**
     * Set felony
     *
     * @param integer $felony
     * @return AdditionalInformation
     */
    public function setFelony($felony)
    {
        $this->felony = $felony;

        return $this;
    }

    /**
     * Get felony
     *
     * @return integer 
     */
    public function getFelony()
    {
        return $this->felony;
    }

    /**
     * Set felonyExplanation
     *
     * @param string $felonyExplanation
     * @return AdditionalInformation
     */
    public function setFelonyExplanation($felonyExplanation)
    {
        $this->felonyExplanation = $felonyExplanation;

        return $this;
    }

    /**
     * Get felonyExplanation
     *
     * @return string 
     */
    public function getFelonyExplanation()
    {
        return $this->felonyExplanation;
    }

    /**
     * Set proofEligibility
     *
     * @param integer $proofEligibility
     * @return AdditionalInformation
     */
    public function setProofEligibility($proofEligibility)
    {
        $this->proofEligibility = $proofEligibility;

        return $this;
    }

    /**
     * Get proofEligibility
     *
     * @return integer 
     */
    public function getProofEligibility()
    {
        return $this->proofEligibility;
    }

    /**
     * Set citezenship
     *
     * @param integer $citezenship
     * @return AdditionalInformation
     */
    public function setCitezenship($citezenship)
    {
        $this->citezenship = $citezenship;

        return $this;
    }

    /**
     * Get citezenship
     *
     * @return integer 
     */
    public function getCitezenship()
    {
        return $this->citezenship;
    }

    /**
     * Set visaType
     *
     * @param string $visaType
     * @return AdditionalInformation
     */
    public function setVisaType($visaType)
    {
        $this->visaType = $visaType;

        return $this;
    }

    /**
     * Get visaType
     *
     * @return string 
     */
    public function getVisaType()
    {
        return $this->visaType;
    }

    /**
     * Set certification
     *
     * @param string $certification
     * @return AdditionalInformation
     */
    public function setCertification($certification)
    {
        $this->certification = $certification;

        return $this;
    }

    /**
     * Get certification
     *
     * @return string 
     */
    public function getCertification()
    {
        return $this->certification;
    }

    /**
     * Set applicant
     *
     * @param \Appform\FrontendBundle\Entity\Applicant $applicant
     * @return AdditionalInformation
     */
    public function setApplicant(\Appform\FrontendBundle\Entity\Applicant $applicant = null)
    {
        $this->applicant = $applicant;

        return $this;
    }

    /**
     * Get applicant
     *
     * @return \Appform\FrontendBundle\Entity\Applicant 
     */
    public function getApplicant()
    {
        return $this->applicant;
    }
}
