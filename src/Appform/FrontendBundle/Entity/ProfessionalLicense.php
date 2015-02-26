<?php

namespace Appform\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProfessionalLicense
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ProfessionalLicense
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
     * @ORM\Column(name="primaryLicense", type="string", length=100)
     */
    private $primaryLicense;


    /**
     * @var string
     *
     * @ORM\Column(name="secondaryLicense", type="string", length=100)
     */
    private $secondaryLicense;

    /**
     * @var string
     *
     * @ORM\Column(name="CPRCertification", type="string", length=100)
     */
    private $CPRCertification;

    /**
     * @var string
     *
     * @ORM\Column(name="advancedCertification", type="string", length=100)
     */
    private $advancedCertification;


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
     * Set primaryLicense
     *
     * @param string $primaryLicense
     * @return ProfessionalLicense
     */
    public function setPrimaryLicense($primaryLicense)
    {
        $this->primaryLicense = $primaryLicense;

        return $this;
    }

    /**
     * Get primaryLicense
     *
     * @return string 
     */
    public function getPrimaryLicense()
    {
        return $this->primaryLicense;
    }

    /**
     * Set secondaryLicense
     *
     * @param string $secondaryLicense
     * @return ProfessionalLicense
     */
    public function setSecondaryLicense($secondaryLicense)
    {
        $this->secondaryLicense = $secondaryLicense;

        return $this;
    }

    /**
     * Get secondaryLicense
     *
     * @return string 
     */
    public function getSecondaryLicense()
    {
        return $this->secondaryLicense;
    }

    /**
     * Set CPRCertification
     *
     * @param string $cPRCertification
     * @return ProfessionalLicense
     */
    public function setCPRCertification($cPRCertification)
    {
        $this->CPRCertification = $cPRCertification;

        return $this;
    }

    /**
     * Get CPRCertification
     *
     * @return string 
     */
    public function getCPRCertification()
    {
        return $this->CPRCertification;
    }

    /**
     * Set advancedCertification
     *
     * @param string $advancedCertification
     * @return ProfessionalLicense
     */
    public function setAdvancedCertification($advancedCertification)
    {
        $this->advancedCertification = $advancedCertification;

        return $this;
    }

    /**
     * Get advancedCertification
     *
     * @return string 
     */
    public function getAdvancedCertification()
    {
        return $this->advancedCertification;
    }

    /**
     * Set applicant
     *
     * @param \Appform\FrontendBundle\Entity\Applicant $applicant
     * @return ProfessionalLicense
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
