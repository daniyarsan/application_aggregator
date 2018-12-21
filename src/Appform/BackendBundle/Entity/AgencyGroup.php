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
	 * @ORM\OneToMany(targetEntity="Campaign", mappedBy="agencygroup", cascade={"all"})
	 */
	protected $campaign;

	/**
	 * @var integer
	 * @ORM\Column(name="sorting", type="integer", nullable=true)
	 */
	private $sorting;

	/**
	 * @var ArrayCollection $agencies
	 * @ORM\ManyToMany(targetEntity="Appform\BackendBundle\Entity\Agency")
	 * @ORM\JoinTable(name="group_agency",
	 *     joinColumns={@ORM\JoinColumn(name="agency_id", referencedColumnName="id", onDelete="CASCADE")},
	 *     inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id", onDelete="CASCADE")},
	 * )
	 */
	protected $agencies;

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

	/**
	 * Set order
	 *
	 * @param integer $order
	 *
	 * @return AgencyGroup
	 */
	public function setOrder($order)
	{
		$this->order = $order;

		return $this;
	}

	/**
	 * Get order
	 *
	 * @return integer
	 */
	public function getOrder()
	{
		return $this->order;
	}

	/**
	 * Set sorting
	 *
	 * @param integer $sorting
	 *
	 * @return AgencyGroup
	 */
	public function setSorting($sorting)
	{
		$this->sorting = $sorting;

		return $this;
	}

	/**
	 * Get sorting
	 *
	 * @return integer
	 */
	public function getSorting()
	{
		return $this->sorting;
	}

	public function addAgency(Agency $agency)
	{
		$this->products->add($agency);

		return $this;
	}

	public function removeAgency(Agency $agency)
	{
		$this->products->removeElement($agency);
	}

	public function getAgencies()
	{
		return $this->agencies;
	}
}
