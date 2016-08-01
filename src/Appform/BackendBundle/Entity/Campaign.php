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
     * @ORM\Column(name="subject", type="string", length=255, nullable=false)
     */
    private $subject;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="publishAt", type="datetime", nullable=false)
     */
    private $publishat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="publishDate", type="datetime", nullable=true)
     */
    private $publishdate;

    /**
     * @var string
     *
     * @ORM\Column(name="isPublished", type="string", length=1)
     */
    private $ispublished = '';

    /**
     * @var \AgencyGroup
     *
     * @ORM\ManyToOne(targetEntity="AgencyGroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="agencygroup_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $agencygroup;

    /**
     * @var integer
     *
     * @ORM\Column(name="applicant", type="integer")
     */
    protected $applicant;

    /**
     *
     *
     * @ORM\Column(name="files", type="array")
     */
    protected $files;



    public function getFiles()
    {
        return $this->files;
    }


    public function setFiles($files)
    {
        $this->files[] = $files;
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
     * Set applicant
     *
     * @param array $applicant
     *
     * @return Campaign
     */
    public function setApplicant($applicant)
    {
        $this->applicant = $applicant;

        return $this;
    }

    /**
     * Get applicant
     *
     * @return array
     */
    public function getApplicant()
    {
        return $this->applicant;
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
