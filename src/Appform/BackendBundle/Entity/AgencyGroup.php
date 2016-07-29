<?php

namespace Appform\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AgencyGroup
 *
 * 
 * @ORM\Entity
 */
class AgencyGroup
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer", name="id")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true, name="Name")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Appform\BackendBundle\Entity\Agency", mappedBy="agencygroup", cascade={"all"})
     */
    protected $agencies;

    /**
     * @ORM\OneToMany(targetEntity="Appform\BackendBundle\Entity\Campaign", mappedBy="agencygroup", cascade={"all"})
     */
    protected $campaign;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->agencies = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * @return AgencyGroup
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    function __toString()
    {
        return $this->getName();
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

    /**
     * Set campaign
     *
     * @param \Appform\BackendBundle\Entity\Campaign $campaign
     *
     * @return AgencyGroup
     */
    public function setCampaign(\Appform\BackendBundle\Entity\Campaign $campaign)
    {
        $this->campaign = $campaign;

        return $this;
    }

    /**
     * Get campaign
     *
     * @return \Appform\BackendBundle\Entity\Campaign
     */
    public function getCampaign()
    {
        return $this->campaign;
    }

    /**
     * Add campaign
     *
     * @param \Appform\BackendBundle\Entity\Campaign $campaign
     *
     * @return AgencyGroup
     */
    public function addCampaign(\Appform\BackendBundle\Entity\Campaign $campaign)
    {
        $this->campaign[] = $campaign;

        return $this;
    }

    /**
     * Remove campaign
     *
     * @param \Appform\BackendBundle\Entity\Campaign $campaign
     */
    public function removeCampaign(\Appform\BackendBundle\Entity\Campaign $campaign)
    {
        $this->campaign->removeElement($campaign);
    }
}
