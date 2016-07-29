<?php

namespace Appform\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mailgroup
 *
 * 
 * @ORM\Entity
 */

class Mailgroup
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
     *
     * @ORM\Column(type="string", length=255, nullable=true, name="title")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true, name="email")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="array", nullable=true, name="origins_list")
     */
    private $originsList;

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
     * Set title
     *
     * @param string $title
     * @return Mailgroup
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Mailgroup
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
     * Set originsList
     *
     * @param array $originsList
     * @return Mailgroup
     */
    public function setOriginsList($originsList)
    {
        $this->originsList = $originsList;

        return $this;
    }

    /**
     * Get originsList
     *
     * @return array 
     */
    public function getOriginsList()
    {
        return $this->originsList;
    }
}
