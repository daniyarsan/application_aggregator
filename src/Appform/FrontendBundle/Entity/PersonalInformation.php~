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
	 * @ORM\Column(name="discipline", type="string", length=255)
	 */
	private $discipline;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="specialtyPrimary", type="string", length=255)
	 */
	private $specialtyPrimary;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="specialtySecondary", type="string", length=255)
	 */
	private $specialtySecondary;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="birthdate", type="datetime")
	 */
	private $birthdate;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="phone", type="string", length=255)
	 */
	private $phone;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="socialSecurityNumber", type="string", length=255)
	 */
	private $socialSecurityNumber;
    /**
	 * @var string
	 *
	 * @ORM\Column(name="preferredContactMethod", type="string", length=255)
	 */
	private $preferredContactMethod;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="resume", type="string", length=255)
	 */
	private $resume;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="experienced", type="string", length=255)
	 */
	private $experienced;
	/**
	 * @var string
	 *
	 * @ORM\Column(name="howhearaboutus", type="string", length=255)
	 */
	private $howhearaboutus;
	/**
	 * @var string
	 *
	 * @ORM\Column(name="timeLicence", type="string", length=255)
	 */
	private $timeLicence;
	/**
	 * @var string
	 *
	 * @ORM\Column(name="address", type="string", length=255)
	 */
	private $address;
	/**
	 * @var string
	 *
	 * @ORM\Column(name="appartment", type="string", length=255)
	 */
	private $appartment;
	/**
	 * @var string
	 *
	 * @ORM\Column(name="city", type="string", length=255)
	 */
	private $city;
	/**
	 * @var string
	 *
	 * @ORM\Column(name="state", type="string", length=255)
	 */
	private $state;
	/**
	 * @var string
	 *
	 * @ORM\Column(name="zipcode", type="string", length=255)
	 */
	private $zipcode;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="isTravelAssignementAddress", type="string", length=255)
	 */
	private $isTravelAssignementAddress;

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
     * Set birthdate
     *
     * @param \DateTime $birthdate
     * @return PersonalInformation
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime 
     */
    public function getBirthdate()
    {
        return $this->birthdate;
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
     * Set socialSecurityNumber
     *
     * @param string $socialSecurityNumber
     * @return PersonalInformation
     */
    public function setSocialSecurityNumber($socialSecurityNumber)
    {
        $this->socialSecurityNumber = $socialSecurityNumber;

        return $this;
    }

    /**
     * Get socialSecurityNumber
     *
     * @return string 
     */
    public function getSocialSecurityNumber()
    {
        return $this->socialSecurityNumber;
    }

    /**
     * Set preferredContactMethod
     *
     * @param string $preferredContactMethod
     * @return PersonalInformation
     */
    public function setPreferredContactMethod($preferredContactMethod)
    {
        $this->preferredContactMethod = $preferredContactMethod;

        return $this;
    }

    /**
     * Get preferredContactMethod
     *
     * @return string 
     */
    public function getPreferredContactMethod()
    {
        return $this->preferredContactMethod;
    }

    /**
     * Set resume
     *
     * @param string $resume
     * @return PersonalInformation
     */
    public function setResume($resume)
    {
        $this->resume = $resume;

        return $this;
    }

    /**
     * Get resume
     *
     * @return string 
     */
    public function getResume()
    {
        return $this->resume;
    }

    /**
     * Set experienced
     *
     * @param string $experienced
     * @return PersonalInformation
     */
    public function setExperienced($experienced)
    {
        $this->experienced = $experienced;

        return $this;
    }

    /**
     * Get experienced
     *
     * @return string 
     */
    public function getExperienced()
    {
        return $this->experienced;
    }

    /**
     * Set howhearaboutus
     *
     * @param string $howhearaboutus
     * @return PersonalInformation
     */
    public function setHowhearaboutus($howhearaboutus)
    {
        $this->howhearaboutus = $howhearaboutus;

        return $this;
    }

    /**
     * Get howhearaboutus
     *
     * @return string 
     */
    public function getHowhearaboutus()
    {
        return $this->howhearaboutus;
    }

    /**
     * Set timeLicence
     *
     * @param string $timeLicence
     * @return PersonalInformation
     */
    public function setTimeLicence($timeLicence)
    {
        $this->timeLicence = $timeLicence;

        return $this;
    }

    /**
     * Get timeLicence
     *
     * @return string 
     */
    public function getTimeLicence()
    {
        return $this->timeLicence;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return PersonalInformation
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set appartment
     *
     * @param string $appartment
     * @return PersonalInformation
     */
    public function setAppartment($appartment)
    {
        $this->appartment = $appartment;

        return $this;
    }

    /**
     * Get appartment
     *
     * @return string 
     */
    public function getAppartment()
    {
        return $this->appartment;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return PersonalInformation
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
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
     * Set zipcode
     *
     * @param string $zipcode
     * @return PersonalInformation
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return string 
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set isTravelAssignementAddress
     *
     * @param string $isTravelAssignementAddress
     * @return PersonalInformation
     */
    public function setIsTravelAssignementAddress($isTravelAssignementAddress)
    {
        $this->isTravelAssignementAddress = $isTravelAssignementAddress;

        return $this;
    }

    /**
     * Get isTravelAssignementAddress
     *
     * @return string 
     */
    public function getIsTravelAssignementAddress()
    {
        return $this->isTravelAssignementAddress;
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
}
