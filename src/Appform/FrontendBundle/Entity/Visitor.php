<?php

namespace Appform\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Visitor
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Appform\FrontendBundle\Entity\VisitorRepository")
 */
class Visitor
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
     * @ORM\Column(name="ip", type="string", length=255)
     */
    private $ip;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_activity", type="datetime")
     */
    private $lastActivity;

    /**
     * @var string
     *
     * @ORM\Column(name="referrer", type="string", length=255, nullable=true)
     */
    private $referrer;

    /**
     * @var string
     *
     * @ORM\Column(name="referrer_url", type="string", length=255, nullable=true)
     */
    private $referrerUrl;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="site_page", type="string", length=255, nullable=true)
	 */
	private $sitePage;



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
     * Set ip
     *
     * @param string $ip
     *
     * @return Visitor
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set lastActivity
     *
     * @param \DateTime $lastActivity
     *
     * @return Visitor
     */
    public function setLastActivity($lastActivity)
    {
        $this->lastActivity = $lastActivity;

        return $this;
    }

    /**
     * Get lastActivity
     *
     * @return \DateTime
     */
    public function getLastActivity()
    {
        return $this->lastActivity;
    }

    /**
     * Set referrer
     *
     * @param string $referrer
     *
     * @return Visitor
     */
    public function setReferrer($referrer)
    {
        $this->referrer = $referrer;

        return $this;
    }

    /**
     * Get referrer
     *
     * @return string
     */
    public function getReferrer()
    {
        return $this->referrer;
    }

    /**
     * Set referrer
     *
     * @param string $referrerUrl
     *
     * @return Visitor
     */
    public function setReferrerUrl($referrerUrl)
    {
        $this->referrerUrl = $referrerUrl;

        return $this;
    }

    /**
     * Get referrerUrl
     *
     * @return string
     */
    public function getReferrerUrl()
    {
        return $this->referrerUrl;
    }

	/**
	 * @return string
	 */
	public function getSitePage()
	{
		return $this->sitePage;
	}

	/**
	 * @param string $sitePage
	 */
	public function setSitePage($sitePage)
	{
		$this->sitePage = $sitePage;
	}

}

