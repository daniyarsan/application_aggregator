<?php

namespace Appform\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AgencyGroup
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class AgencyGroup
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
     * @ORM\Column(name="Name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Agency", mappedBy="agencies")
     */
    protected $agencies;

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
     * Constructor
     */
    public function __construct()
    {
        $this->agencies = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return AgencyGroup
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
     * Add agency
     *
     * @param \Appform\BackendBundle\Entity\Agency $agency
     *
     * @return AgencyGroup
     */
    public function addAgency(\Appform\BackendBundle\Entity\Agency $agency)
    {
        $this->agencies[] = $agency;

        return $this;
    }

    /**
     * Remove agency
     *
     * @param \Appform\BackendBundle\Entity\Agency $agency
     */
    public function removeAgency(\Appform\BackendBundle\Entity\Agency $agency)
    {
        $this->agencies->removeElement($agency);
    }

    /**
     * Get agencies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAgencies()
    {
        return $this->agencies;
    }
}
