<?php

namespace Appform\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Campaign
 *
 * @ORM\Table(name="Campaign")
 * @ORM\Entity
 */
class Campaign
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name = '';

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255, nullable=true)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", length=65535, nullable=true)
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="publishAt", type="datetime", nullable=true)
     */
    private $publishat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="publishDate", type="datetime", nullable=false)
     */
    private $publishdate;

    /**
     * @var string
     *
     * @ORM\Column(name="isPublished", type="string", length=1, nullable=false)
     */
    private $ispublished = '';

    /**
     * @var \AgencyGroup
     *
     * @ORM\ManyToOne(targetEntity="AgencyGroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="agencygroup_id", referencedColumnName="id")
     * })
     */
    private $agencygroup;

    /**
     * @var array
     *
     * @ORM\Column(name="applicants", type="array", nullable=true)
     */
    protected $applicants;


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
     * @return Campaign
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
     * Set subject
     *
     * @param string $subject
     *
     * @return Campaign
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Campaign
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set publishat
     *
     * @param \DateTime $publishat
     *
     * @return Campaign
     */
    public function setPublishat($publishat)
    {
        $this->publishat = $publishat;

        return $this;
    }

    /**
     * Get publishat
     *
     * @return \DateTime
     */
    public function getPublishat()
    {
        return $this->publishat;
    }

    /**
     * Set publishdate
     *
     * @param \DateTime $publishdate
     *
     * @return Campaign
     */
    public function setPublishdate($publishdate)
    {
        $this->publishdate = $publishdate;

        return $this;
    }

    /**
     * Get publishdate
     *
     * @return \DateTime
     */
    public function getPublishdate()
    {
        return $this->publishdate;
    }

    /**
     * Set ispublished
     *
     * @param string $ispublished
     *
     * @return Campaign
     */
    public function setIspublished($ispublished)
    {
        $this->ispublished = $ispublished;

        return $this;
    }

    /**
     * Get ispublished
     *
     * @return string
     */
    public function getIspublished()
    {
        return $this->ispublished;
    }

    /**
     * Set applicants
     *
     * @param array $applicants
     *
     * @return Campaign
     */
    public function setApplicants($applicants)
    {
        $this->applicants = $applicants;

        return $this;
    }

    /**
     * Get applicants
     *
     * @return array
     */
    public function getApplicants()
    {
        return $this->applicants;
    }

    /**
     * Set agencygroup
     *
     * @param \Appform\BackendBundle\Entity\AgencyGroup $agencygroup
     *
     * @return Campaign
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
}
