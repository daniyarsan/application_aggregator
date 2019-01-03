<?php

namespace Appform\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Applicant
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Appform\FrontendBundle\Repository\ApplicantRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Applicant
{
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 *
	 */

	private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="candidateId", type="integer", length=6)
	 */
	private $candidateId;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="firstName", type="string", length=255)
	 */
	private $firstName;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="lastName", type="string", length=255)
	 */
	private $lastName;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="email", type="string", length=255)
	 */
	private $email;

	/** @ORM\OneToOne(targetEntity="PersonalInformation", mappedBy="applicant", cascade={"remove", "merge"}) */
	protected $personalInformation;


	/** @ORM\OneToOne(targetEntity="Document", mappedBy="applicant",  cascade={"remove"}) */
	protected $document;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="created", type="datetime")
	 */
	private $created;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="ip", type="bigint", length=6)
	 */
	private $ip;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="app_origin", type="string", length=255, nullable=true)
	 */
	private $appOrigin;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="app_referer", type="string", length=255, nullable=true)
	 */
	private $appReferer;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="ref_url", type="string", length=255, nullable=true)
	 */
	private $refUrl;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="token", type="string", length=255, nullable=true)
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
	 * Set firstName
	 *
	 * @param string $firstName
	 * @return Applicant
	 */
	public function setFirstName($firstName)
	{
		$this->firstName = $firstName;

		return $this;
	}

	/**
	 * Get firstName
	 *
	 * @return string
	 */
	public function getFirstName()
	{
		return $this->firstName;
	}

	/**
	 * Set lastName
	 *
	 * @param string $lastName
	 * @return Applicant
	 */
	public function setLastName($lastName)
	{
		$this->lastName = $lastName;

		return $this;
	}

	/**
	 * Get lastName
	 *
	 * @return string
	 */
	public function getLastName()
	{
		return $this->lastName;
	}

	/**
	 * Set email
	 *
	 * @param string $email
	 * @return Applicant
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

	/**
	 * Set personalInformation
	 *
	 * @param \Appform\FrontendBundle\Entity\PersonalInformation $personalInformation
	 * @return Applicant
	 */
	public function setPersonalInformation(\Appform\FrontendBundle\Entity\PersonalInformation $personalInformation = null)
	{
		$this->personalInformation = $personalInformation;

		return $this;
	}

	/**
	 * Get personalInformation
	 *
	 * @return \Appform\FrontendBundle\Entity\PersonalInformation
	 */
	public function getPersonalInformation()
	{
		return $this->personalInformation;
	}

	/**
	 * Set candidateId
	 *
	 * @param string $candidateId
	 * @return Applicant
	 */
	public function setCandidateId($candidateId)
	{
		$this->candidateId = $candidateId;

		return $this;
	}

	/**
	 * Get candidateId
	 *
	 * @return string
	 */
	public function getCandidateId()
	{
		return $this->candidateId;
	}

	/**
	 * Set ip
	 *
	 * @param string ip
	 * @return Applicant
	 */
	public function setIp($ip)
	{
		$this->ip = ip2long($ip);

		return $this;
	}

	/**
	 * Get ip
	 *
	 * @return string
	 */
	public function getIp()
	{
		return long2ip($this->ip);
	}

	/**
	 * Set document
	 *
	 * @param \Appform\FrontendBundle\Entity\Document $document
	 * @return Applicant
	 */
	public function setDocument(\Appform\FrontendBundle\Entity\Document $document = null)
	{
		$this->document = $document;

		return $this;
	}

	/**
	 * Get document
	 *
	 * @return \Appform\FrontendBundle\Entity\Document
	 */
	public function getDocument()
	{
		return $this->document;
	}

	/**
	 * @return mixed
	 */
	public function __toString()
	{
		return (string)$this->getFirstName();
	}

	/**
	 * @return string
	 */
	public function getCreated()
	{
		return $this->created;
	}

	/**
	 * Set created
	 *
	 * @ORM\PrePersist
	 *
	 * @return Applicant
	 *
	 */
	public function setCreated()
	{
		$this->created = new \DateTime();
	}


	/**
	 * Set appOrigin
	 *
	 * @param string $appOrigin
	 * @return Applicant
	 */
	public function setAppOrigin($appOrigin)
	{
		$this->appOrigin = $appOrigin;

		return $this;
	}

	/**
	 * Get appOrigin
	 *
	 * @return string
	 */
	public function getAppOrigin()
	{
		return $this->appOrigin;
	}

	/**
	 * Set appReferer
	 *
	 * @param string $appReferer
	 * @return Applicant
	 */
	public function setAppReferer($appReferer)
	{
		$this->appReferer = $appReferer;

		return $this;
	}

	/**
	 * Get appReferer
	 *
	 * @return string
	 */
	public function getAppReferer()
	{
		return $this->appReferer;
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
