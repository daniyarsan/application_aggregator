<?php

namespace Appform\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Agency
 *
 * @ORM\Table(name="Agency", indexes={@ORM\Index(name="IDX_776CC3D056AC33D", columns={"agencyGroup_id"})})
 * @ORM\Entity
 */
class Agency
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer", name="id")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false, name="Name")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true, name="Email")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true, name="Description")
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true, name="Active")
     */
    private $active;

    /**
     * @var \AgencyGroup
     *
     * @ORM\ManyToOne(targetEntity="Appform\BackendBundle\Entity\AgencyGroup", inversedBy="agencies")
     * @ORM\JoinColumn(name="agencyGroup_id", referencedColumnName="id", onDelete="SET NULL")
     * 
     */
    private $agencygroup;



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
     *
     * @return Agency
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
     * Set description
     *
     * @param string $description
     *
     * @return Agency
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Agency
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set agencygroup
     *
     * @param \Appform\BackendBundle\Entity\AgencyGroup $agencygroup
     *
     * @return Agency
     */
    public function setAgencygroup(\Appform\BackendBundle\Entity\AgencyGroup $agencygroup = null)
    {
        $this->agencygroup = $agencygroup;

        return $this;
    }

    /**
     * Get agencygroup
     *
     * @return \Appform\BackendBundle\Entity\AgencyGroup
     */
    public function getAgencygroup()
    {
        return $this->agencygroup;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Agency
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
}
