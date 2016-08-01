<?php

namespace Appform\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mailgroup
 *
 * @ORM\Table()
 * @ORM\Entity
 */

class Mailgroup
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="origins_list", type="array", nullable=true)
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
