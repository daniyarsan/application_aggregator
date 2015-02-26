<?php

namespace Appform\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WorkPreferences
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class WorkPreferences
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
     * @ORM\Column(name="typeOfAssignements", type="string", length=255)
     */
    private $typeOfAssignements;

    /**
     * @var string
     *
     * @ORM\Column(name="preferredShifts", type="string", length=255)
     */
    private $preferredShifts;


    /**
     * @var string
     *
     * @ORM\Column(name="opportunitiesInStates", type="string", length=10)
     */
    private $opportunitiesInStates;

    /**
     * @var string
     *
     * @ORM\Column(name="travelAssignementStatus", type="string", length=10)
     */
    private $travelAssignementStatus;

        /**
         * @var string
         *
         * @ORM\Column(name="facility", type="string", length=10)
         */
    private $facility;


        /**
         * @var string
         *
         * @ORM\Column(name="state", type="string", length=100)
         */
    private $state;

        /**
         * @var string
         *
         * @ORM\Column(name="completion", type="string", length=100)
         */
    private $completion;

        /**
         * @var string
         *
         * @ORM\Column(name="assignementStartTime", type="string", length=10)
         */
    private $assignementStartTime;

    /**
     * @var string
     *
     * @ORM\Column(name="takeVehicle", type="string", length=10)
     */
    private $takeVehicle;

    /**
     * @var string
     *
     * @ORM\Column(name="takeFriends", type="string", length=10)
     */
    private $takeFriends;

    /**
     * @var string
     *
     * @ORM\Column(name="takePets", type="string", length=10)
     */
    private $takePets;

    /**
     * @var string
     *
     * @ORM\Column(name="petsType", type="string", length=10)
     */
    private $petsType;

    /**
     * @var string
     *
     * @ORM\Column(name="petsWeight", type="string", length=10)
     */
    private $petsWeight;


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
     * Set typeOfAssignements
     *
     * @param string $typeOfAssignements
     * @return WorkPreferences
     */
    public function setTypeOfAssignements($typeOfAssignements)
    {
        $this->typeOfAssignements = $typeOfAssignements;

        return $this;
    }

    /**
     * Get typeOfAssignements
     *
     * @return string 
     */
    public function getTypeOfAssignements()
    {
        return $this->typeOfAssignements;
    }

    /**
     * Set preferredShifts
     *
     * @param string $preferredShifts
     * @return WorkPreferences
     */
    public function setPreferredShifts($preferredShifts)
    {
        $this->preferredShifts = $preferredShifts;

        return $this;
    }

    /**
     * Get preferredShifts
     *
     * @return string 
     */
    public function getPreferredShifts()
    {
        return $this->preferredShifts;
    }

    /**
     * Set opportunitiesInStates
     *
     * @param string $opportunitiesInStates
     * @return WorkPreferences
     */
    public function setOpportunitiesInStates($opportunitiesInStates)
    {
        $this->opportunitiesInStates = $opportunitiesInStates;

        return $this;
    }

    /**
     * Get opportunitiesInStates
     *
     * @return string 
     */
    public function getOpportunitiesInStates()
    {
        return $this->opportunitiesInStates;
    }

    /**
     * Set travelAssignementStatus
     *
     * @param string $travelAssignementStatus
     * @return WorkPreferences
     */
    public function setTravelAssignementStatus($travelAssignementStatus)
    {
        $this->travelAssignementStatus = $travelAssignementStatus;

        return $this;
    }

    /**
     * Get travelAssignementStatus
     *
     * @return string 
     */
    public function getTravelAssignementStatus()
    {
        return $this->travelAssignementStatus;
    }

    /**
     * Set facility
     *
     * @param string $facility
     * @return WorkPreferences
     */
    public function setFacility($facility)
    {
        $this->facility = $facility;

        return $this;
    }

    /**
     * Get facility
     *
     * @return string 
     */
    public function getFacility()
    {
        return $this->facility;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return WorkPreferences
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
     * Set completion
     *
     * @param string $completion
     * @return WorkPreferences
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

    /**
     * Set assignementStartTime
     *
     * @param string $assignementStartTime
     * @return WorkPreferences
     */
    public function setAssignementStartTime($assignementStartTime)
    {
        $this->assignementStartTime = $assignementStartTime;

        return $this;
    }

    /**
     * Get assignementStartTime
     *
     * @return string 
     */
    public function getAssignementStartTime()
    {
        return $this->assignementStartTime;
    }

    /**
     * Set takeVehicle
     *
     * @param string $takeVehicle
     * @return WorkPreferences
     */
    public function setTakeVehicle($takeVehicle)
    {
        $this->takeVehicle = $takeVehicle;

        return $this;
    }

    /**
     * Get takeVehicle
     *
     * @return string 
     */
    public function getTakeVehicle()
    {
        return $this->takeVehicle;
    }

    /**
     * Set takeFriends
     *
     * @param string $takeFriends
     * @return WorkPreferences
     */
    public function setTakeFriends($takeFriends)
    {
        $this->takeFriends = $takeFriends;

        return $this;
    }

    /**
     * Get takeFriends
     *
     * @return string 
     */
    public function getTakeFriends()
    {
        return $this->takeFriends;
    }

    /**
     * Set takePets
     *
     * @param string $takePets
     * @return WorkPreferences
     */
    public function setTakePets($takePets)
    {
        $this->takePets = $takePets;

        return $this;
    }

    /**
     * Get takePets
     *
     * @return string 
     */
    public function getTakePets()
    {
        return $this->takePets;
    }

    /**
     * Set petsType
     *
     * @param string $petsType
     * @return WorkPreferences
     */
    public function setPetsType($petsType)
    {
        $this->petsType = $petsType;

        return $this;
    }

    /**
     * Get petsType
     *
     * @return string 
     */
    public function getPetsType()
    {
        return $this->petsType;
    }

    /**
     * Set petsWeight
     *
     * @param string $petsWeight
     * @return WorkPreferences
     */
    public function setPetsWeight($petsWeight)
    {
        $this->petsWeight = $petsWeight;

        return $this;
    }

    /**
     * Get petsWeight
     *
     * @return string 
     */
    public function getPetsWeight()
    {
        return $this->petsWeight;
    }

    /**
     * Set applicant
     *
     * @param \Appform\FrontendBundle\Entity\Applicant $applicant
     * @return WorkPreferences
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
