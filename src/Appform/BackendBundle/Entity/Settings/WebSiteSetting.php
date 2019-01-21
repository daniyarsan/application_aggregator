<?php

namespace Appform\BackendBundle\Entity\Settings;

use Doctrine\ORM\Mapping as ORM;

/**
 * WebSiteSetting
 *
 * @ORM\MappedSuperclass
 */
class WebSiteSetting
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255, nullable=true)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_for_ban", type="string", length=255, nullable=true)
     */
    private $ipForBan;

    /**
     * @return string
     */
    public function getIpForBan()
    {
        return $this->ipForBan;
    }

    /**
     * @param string $ipForBan
     */
    public function setIpForBan($ipForBan)
    {
        $this->ipForBan = $ipForBan;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=255, nullable=true)
     */
    private $domainForBan;

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $subject
     */
    public function setSubject ($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getDomainForBan()
    {
        return $this->domainForBan;
    }

    /**
     * @param string $domainForBan
     */
    public function setDomainForBan($domainForBan)
    {
        $this->domainForBan = $domainForBan;
    }
}
