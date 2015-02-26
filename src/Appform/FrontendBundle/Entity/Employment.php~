<?php

namespace Appform\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Employment
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Employment
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
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="startDate", type="datetime")
     */
    private $startDate;

    /**
     * @var string
     *
     * @ORM\Column(name="endDate", type="datetime")
     */
    private $endDate;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=100)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="isContactable", type="string", length=10)
     */
    private $isContactable;

    /**
     * @var string
     *
     * @ORM\Column(name="wasTravelAssignement", type="string", length=100)
     */
    private $wasTravelAssignement;

    /**
     * @var string
     *
     * @ORM\Column(name="position", type="string", length=100)
     */
    private $position;
    /**
     * @var string
     *
     * @ORM\Column(name="specialty", type="string", length=100)
     */
    private $specialty;
    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=100)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=100)
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="supervisor", type="string", length=100)
     */
    private $supervisor;

    /**
     * @var string
     *
     * @ORM\Column(name="supervisorTitle", type="string", length=100)
     */
    private $supervisorTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="supervisorPhone", type="string", length=100)
     */
    private $supervisorPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="leavingReason", type="string", length=100)
     */
    private $leavingReason;

    /**
     * @var string
     *
     * @ORM\Column(name="dutyDescription", type="string", length=100)
     */
    private $dutyDescription;


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
     * Set name
     *
     * @param string $name
     * @return Employment
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Employment
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return Employment
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Employment
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
     * Set isContactable
     *
     * @param string $isContactable
     * @return Employment
     */
    public function setIsContactable($isContactable)
    {
        $this->isContactable = $isContactable;

        return $this;
    }

    /**
     * Get isContactable
     *
     * @return string 
     */
    public function getIsContactable()
    {
        return $this->isContactable;
    }

    /**
     * Set wasTravelAssignement
     *
     * @param string $wasTravelAssignement
     * @return Employment
     */
    public function setWasTravelAssignement($wasTravelAssignement)
    {
        $this->wasTravelAssignement = $wasTravelAssignement;

        return $this;
    }

    /**
     * Get wasTravelAssignement
     *
     * @return string 
     */
    public function getWasTravelAssignement()
    {
        return $this->wasTravelAssignement;
    }

    /**
     * Set position
     *
     * @param string $position
     * @return Employment
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set specialty
     *
     * @param string $specialty
     * @return Employment
     */
    public function setSpecialty($specialty)
    {
        $this->specialty = $specialty;

        return $this;
    }

    /**
     * Get specialty
     *
     * @return string 
     */
    public function getSpecialty()
    {
        return $this->specialty;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Employment
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
     * @return Employment
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
     * Set supervisor
     *
     * @param string $supervisor
     * @return Employment
     */
    public function setSupervisor($supervisor)
    {
        $this->supervisor = $supervisor;

        return $this;
    }

    /**
     * Get supervisor
     *
     * @return string 
     */
    public function getSupervisor()
    {
        return $this->supervisor;
    }

    /**
     * Set supervisorTitle
     *
     * @param string $supervisorTitle
     * @return Employment
     */
    public function setSupervisorTitle($supervisorTitle)
    {
        $this->supervisorTitle = $supervisorTitle;

        return $this;
    }

    /**
     * Get supervisorTitle
     *
     * @return string 
     */
    public function getSupervisorTitle()
    {
        return $this->supervisorTitle;
    }

    /**
     * Set supervisorPhone
     *
     * @param string $supervisorPhone
     * @return Employment
     */
    public function setSupervisorPhone($supervisorPhone)
    {
        $this->supervisorPhone = $supervisorPhone;

        return $this;
    }

    /**
     * Get supervisorPhone
     *
     * @return string 
     */
    public function getSupervisorPhone()
    {
        return $this->supervisorPhone;
    }

    /**
     * Set leavingReason
     *
     * @param string $leavingReason
     * @return Employment
     */
    public function setLeavingReason($leavingReason)
    {
        $this->leavingReason = $leavingReason;

        return $this;
    }

    /**
     * Get leavingReason
     *
     * @return string 
     */
    public function getLeavingReason()
    {
        return $this->leavingReason;
    }

    /**
     * Set dutyDescription
     *
     * @param string $dutyDescription
     * @return Employment
     */
    public function setDutyDescription($dutyDescription)
    {
        $this->dutyDescription = $dutyDescription;

        return $this;
    }

    /**
     * Get dutyDescription
     *
     * @return string 
     */
    public function getDutyDescription()
    {
        return $this->dutyDescription;
    }

    /**
     * Set applicant
     *
     * @param \Appform\FrontendBundle\Entity\Applicant $applicant
     * @return Employment
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
