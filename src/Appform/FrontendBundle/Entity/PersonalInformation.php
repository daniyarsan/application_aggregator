<?php

namespace Appform\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * PersonalInformation
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class PersonalInformation {
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
     * @ORM\Column(name="phone", type="string", length=255)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255)
     */
    private $state;

 	/**
	 * @var string
	 *
	 * @ORM\Column(name="discipline", type="string", length=255)
	 */
	private $discipline;

    /**
     * @var string
     *
     * @ORM\Column(name="licenseState", type="array")
     */
    private $licenseState;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="specialtyPrimary", type="string", length=100)
	 */
	private $specialtyPrimary;

    /**
     * @var string
     *
     * @ORM\Column(name="yearsLicenceSp", type="string", length=100, nullable=true)
     */
    private $yearsLicenceSp;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="specialtySecondary", type="string", length=255)
	 */
	private $specialtySecondary;

    /**
     * @var string
     *
     * @ORM\Column(name="yearsLicenceSs", type="string", length=100, nullable=true)
     */
    private $yearsLicenceSs;

    /**
     * @var string
     *
     * @ORM\Column(name="desiredAssignementState", type="array")
     */
    private $desiredAssignementState;

    /**
     * @ORM\Column(name="isExperiencedTraveler", type="boolean")
     */
    protected $isExperiencedTraveler = false;

    /**
     * @ORM\Column(name="isOnAssignement", type="boolean")
     */
    protected $isOnAssignement = false;


    /**
     * @var string
     *
     * @ORM\Column(name="assignementTime", type="string", length=255)
     */
    private $assignementTime;

    /**
     * @var string
     *
     * @ORM\Column(name="question", type="text")
     */
    private $question;

    /**
     * @var string
     *
     * @ORM\Column(name="completion", type="date", length=100, nullable=true)
     */
    private $completion;


    /**
     * @ORM\OneToOne(targetEntity="Applicant", inversedBy="personalInformation")
     * @ORM\JoinColumn(name="applicant_id",referencedColumnName="id")
     *
     */
    protected $applicant;


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
     * Set phone
     *
     * @param string $phone
     * @return PersonalInformation
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return PersonalInformation
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set discipline
     *
     * @param string $discipline
     * @return PersonalInformation
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
     * Set licenseState
     *
     * @param string $licenseState
     * @return PersonalInformation
     */
    public function setLicenseState($licenseState)
    {
        $this->licenseState = $licenseState;

        return $this;
    }

    /**
     * Get licenseState
     *
     * @return string 
     */
    public function getLicenseState()
    {
        return $this->licenseState;
    }

    /**
     * Set specialtyPrimary
     *
     * @param string $specialtyPrimary
     * @return PersonalInformation
     */
    public function setSpecialtyPrimary($specialtyPrimary)
    {
        $this->specialtyPrimary = $specialtyPrimary;

        return $this;
    }

    /**
     * Get specialtyPrimary
     *
     * @return string 
     */
    public function getSpecialtyPrimary()
    {
        return $this->specialtyPrimary;
    }

    /**
     * Set yearsLicenceSp
     *
     * @param string $yearsLicenceSp
     * @return PersonalInformation
     */
    public function setYearsLicenceSp($yearsLicenceSp)
    {
        $this->yearsLicenceSp = $yearsLicenceSp;

        return $this;
    }

    /**
     * Get yearsLicenceSp
     *
     * @return string 
     */
    public function getYearsLicenceSp()
    {
        return $this->yearsLicenceSp;
    }

    /**
     * Set specialtySecondary
     *
     * @param string $specialtySecondary
     * @return PersonalInformation
     */
    public function setSpecialtySecondary($specialtySecondary)
    {
        $this->specialtySecondary = $specialtySecondary;

        return $this;
    }

    /**
     * Get specialtySecondary
     *
     * @return string 
     */
    public function getSpecialtySecondary()
    {
        return $this->specialtySecondary;
    }

    /**
     * Set yearsLicenceSs
     *
     * @param string $yearsLicenceSs
     * @return PersonalInformation
     */
    public function setYearsLicenceSs($yearsLicenceSs)
    {
        $this->yearsLicenceSs = $yearsLicenceSs;

        return $this;
    }

    /**
     * Get yearsLicenceSs
     *
     * @return string 
     */
    public function getYearsLicenceSs()
    {
        return $this->yearsLicenceSs;
    }

    /**
     * Set desiredAssignementState
     *
     * @param string $desiredAssignementState
     * @return PersonalInformation
     */
    public function setDesiredAssignementState($desiredAssignementState)
    {
        $this->desiredAssignementState = $desiredAssignementState;

        return $this;
    }

    /**
     * Get desiredAssignementState
     *
     * @return string 
     */
    public function getDesiredAssignementState()
    {
        return $this->desiredAssignementState;
    }

    /**
     * Set isExperiencedTraveler
     *
     * @param boolean $isExperiencedTraveler
     * @return PersonalInformation
     */
    public function setIsExperiencedTraveler($isExperiencedTraveler)
    {
        $this->isExperiencedTraveler = $isExperiencedTraveler;

        return $this;
    }

    /**
     * Get isExperiencedTraveler
     *
     * @return boolean 
     */
    public function getIsExperiencedTraveler()
    {
        return $this->isExperiencedTraveler;
    }

    /**
     * Set isOnAssignement
     *
     * @param boolean $isOnAssignement
     * @return PersonalInformation
     */
    public function setIsOnAssignement($isOnAssignement)
    {
        $this->isOnAssignement = $isOnAssignement;

        return $this;
    }

    /**
     * Get isOnAssignement
     *
     * @return boolean 
     */
    public function getIsOnAssignement()
    {
        return $this->isOnAssignement;
    }

    /**
     * Set assignementTime
     *
     * @param string $assignementTime
     * @return PersonalInformation
     */
    public function setAssignementTime($assignementTime)
    {
        $this->assignementTime = $assignementTime;

        return $this;
    }

    /**
     * Get assignementTime
     *
     * @return string 
     */
    public function getAssignementTime()
    {
        return $this->assignementTime;
    }

    /**
     * Set question
     *
     * @param string $question
     * @return PersonalInformation
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return string 
     */
    public function getQuestion()
    {
        return $this->question;
    }


    /**
     * Set applicant
     *
     * @param \Appform\FrontendBundle\Entity\Applicant $applicant
     * @return PersonalInformation
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

    /**
     * Set completion
     *
     * @param string $completion
     * @return PersonalInformation
     */
    public function setCompletion($completion)
    {
        $this->completion = $completion;

        return $this;
    }

    /**
     * Get completion
     *
     * @return string 
     */
    public function getCompletion()
    {
        return $this->completion;
    }
}
