<?php

namespace Appform\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EmergencyContacts
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class EmergencyContacts
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
     * @ORM\Column(name="contactName", type="string", length=100)
     */
    private $contactName;

    /**
     * @var string
     *
     * @ORM\Column(name="contactPhone", type="string", length=100)
     */

    private $contactPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="contactRelationship", type="string", length=100)
     */
    private $contactRelationship;

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
     * Set contactName
     *
     * @param string $contactName
     * @return EmergencyContacts
     */
    public function setContactName($contactName)
    {
        $this->contactName = $contactName;

        return $this;
    }

    /**
     * Get contactName
     *
     * @return string 
     */
    public function getContactName()
    {
        return $this->contactName;
    }

    /**
     * Set contactPhone
     *
     * @param string $contactPhone
     * @return EmergencyContacts
     */
    public function setContactPhone($contactPhone)
    {
        $this->contactPhone = $contactPhone;

        return $this;
    }

    /**
     * Get contactPhone
     *
     * @return string 
     */
    public function getContactPhone()
    {
        return $this->contactPhone;
    }

    /**
     * Set contactRelationship
     *
     * @param string $contactRelationship
     * @return EmergencyContacts
     */
    public function setContactRelationship($contactRelationship)
    {
        $this->contactRelationship = $contactRelationship;

        return $this;
    }

    /**
     * Get contactRelationship
     *
     * @return string 
     */
    public function getContactRelationship()
    {
        return $this->contactRelationship;
    }

    /**
     * Set applicant
     *
     * @param \Appform\FrontendBundle\Entity\Applicant $applicant
     * @return EmergencyContacts
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
