<?php

namespace Appform\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Applicant
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Applicant
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
     * @ORM\Column(name="firstName", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="middleName", type="string", length=255)
     */
    private $middleName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @ORM\OneToOne(targetEntity="PersonalInformation", mappedBy="applicant", cascade={"persist", "remove"})
     */
    private $personalInformation;

    /**
     * @ORM\OneToOne(targetEntity="AdditionalInformation", mappedBy="applicant", cascade={"persist", "remove"})
     */
    private $additionalInformation;

    /**
     * @ORM\OneToOne(targetEntity="EmergencyContacts", mappedBy="applicant", cascade={"persist", "remove"})
     */
    private $emergencyContacts;


    /**
     * @ORM\OneToOne(targetEntity="ProfessionalLicense", mappedBy="applicant", cascade={"persist", "remove"})
     */
    private $professionalLicense;

    /**
     * @ORM\OneToOne(targetEntity="WorkPreferences", mappedBy="applicant", cascade={"persist", "remove"})
     */
    private $workPreferences;


    /**
     * @ORM\OneToOne(targetEntity="Education", mappedBy="applicant", cascade={"persist", "remove"})
     */
    private $education;


    /**
     * @ORM\OneToOne(targetEntity="Employment", mappedBy="applicant", cascade={"persist", "remove"})
     */
    private $employment;


    /**
     * @ORM\OneToOne(targetEntity="ProfessionalLicense", mappedBy="applicant", cascade={"persist", "remove"})
     */
    private $professionalReference;


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
     * Set personalInformation
     *
     * @param \Appform\FrontendBundle\Entity\PersonalInformation $personalInformation
     * @return Applicant
     */
    public function setPersonalInformation(\Appform\FrontendBundle\Entity\PersonalInformation $personalInformation = null)
    {
        $this->personalInformation = $personalInformation;

        return $this;
    }

    /**
     * Get personalInformation
     *
     * @return \Appform\FrontendBundle\Entity\PersonalInformation 
     */
    public function getPersonalInformation()
    {
        return $this->personalInformation;
    }

    /**
     * Set additionalInformation
     *
     * @param \Appform\FrontendBundle\Entity\AdditionalInformation $additionalInformation
     * @return Applicant
     */
    public function setAdditionalInformation(\Appform\FrontendBundle\Entity\AdditionalInformation $additionalInformation = null)
    {
        $this->additionalInformation = $additionalInformation;

        return $this;
    }

    /**
     * Get additionalInformation
     *
     * @return \Appform\FrontendBundle\Entity\AdditionalInformation 
     */
    public function getAdditionalInformation()
    {
        return $this->additionalInformation;
    }

    /**
     * Set emergencyContacts
     *
     * @param \Appform\FrontendBundle\Entity\EmergencyContacts $emergencyContacts
     * @return Applicant
     */
    public function setEmergencyContacts(\Appform\FrontendBundle\Entity\EmergencyContacts $emergencyContacts = null)
    {
        $this->emergencyContacts = $emergencyContacts;

        return $this;
    }

    /**
     * Get emergencyContacts
     *
     * @return \Appform\FrontendBundle\Entity\EmergencyContacts 
     */
    public function getEmergencyContacts()
    {
        return $this->emergencyContacts;
    }

    /**
     * Set professionalLicense
     *
     * @param \Appform\FrontendBundle\Entity\ProfessionalLicense $professionalLicense
     * @return Applicant
     */
    public function setProfessionalLicense(\Appform\FrontendBundle\Entity\ProfessionalLicense $professionalLicense = null)
    {
        $this->professionalLicense = $professionalLicense;

        return $this;
    }

    /**
     * Get professionalLicense
     *
     * @return \Appform\FrontendBundle\Entity\ProfessionalLicense 
     */
    public function getProfessionalLicense()
    {
        return $this->professionalLicense;
    }

    /**
     * Set workPreferences
     *
     * @param \Appform\FrontendBundle\Entity\WorkPreferences $workPreferences
     * @return Applicant
     */
    public function setWorkPreferences(\Appform\FrontendBundle\Entity\WorkPreferences $workPreferences = null)
    {
        $this->workPreferences = $workPreferences;

        return $this;
    }

    /**
     * Get workPreferences
     *
     * @return \Appform\FrontendBundle\Entity\WorkPreferences 
     */
    public function getWorkPreferences()
    {
        return $this->workPreferences;
    }

    /**
     * Set education
     *
     * @param \Appform\FrontendBundle\Entity\Education $education
     * @return Applicant
     */
    public function setEducation(\Appform\FrontendBundle\Entity\Education $education = null)
    {
        $this->education = $education;

        return $this;
    }

    /**
     * Get education
     *
     * @return \Appform\FrontendBundle\Entity\Education 
     */
    public function getEducation()
    {
        return $this->education;
    }

    /**
     * Set employment
     *
     * @param \Appform\FrontendBundle\Entity\Employment $employment
     * @return Applicant
     */
    public function setEmployment(\Appform\FrontendBundle\Entity\Employment $employment = null)
    {
        $this->employment = $employment;

        return $this;
    }

    /**
     * Get employment
     *
     * @return \Appform\FrontendBundle\Entity\Employment 
     */
    public function getEmployment()
    {
        return $this->employment;
    }

    /**
     * Set professionalReference
     *
     * @param \Appform\FrontendBundle\Entity\ProfessionalLicense $professionalReference
     * @return Applicant
     */
    public function setProfessionalReference(\Appform\FrontendBundle\Entity\ProfessionalReference  $professionalReference = null)
    {
        $this->professionalReference = $professionalReference;

        return $this;
    }

    /**
     * Get professionalReference
     *
     * @return \Appform\FrontendBundle\Entity\ProfessionalLicense 
     */
    public function getProfessionalReference()
    {
        return $this->professionalReference;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Applicant
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return Applicant
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set middleName
     *
     * @param string $middleName
     * @return Applicant
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;

        return $this;
    }

    /**
     * Get middleName
     *
     * @return string 
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return Applicant
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }
}
