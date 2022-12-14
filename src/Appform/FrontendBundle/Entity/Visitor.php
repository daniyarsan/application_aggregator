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
	 * @ORM\Column(name="ref_url", type="string", length=255, nullable=true)
	 */
	private $refUrl;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="discipline", type="string", length=255, nullable=true)
	 */
	private $discipline;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="user_id", type="integer", length=255, nullable=true)
	 */
	private $user_id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="token", type="string", length=255)
	 */
	private $token;


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
	 * @return string
	 */
	public function getRefUrl()
	{
		return $this->refUrl;
	}

	/**
	 * @param string $refUrl
	 */
	public function setRefUrl($refUrl)
	{
		$this->refUrl = $refUrl;
	}

	/**
	 * @return string
	 */
	public function getUserId()
	{
		return $this->user_id;
	}

	/**
	 * @param string $user_id
	 */
	public function setUserId($user_id)
	{
		$this->user_id = $user_id;
	}

	/**
	 * @return string
	 */
	public function getDiscipline()
	{
		return $this->discipline;
	}

	/**
	 * @param string $discipline
	 */
	public function setDiscipline($discipline)
	{
		$this->discipline = $discipline;
	}

	/**
	 * @return string
	 */
	public function getToken()
	{
		return $this->token;
	}

	/**
	 * @param string $token
	 */
	public function setToken($token)
	{
		$this->token = $token;
	}
}

