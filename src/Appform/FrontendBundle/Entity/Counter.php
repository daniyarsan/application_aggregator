<?php

namespace Appform\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Counter
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Appform\FrontendBundle\Entity\CounterRepository")
 */
class Counter
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
     * @var \DateTime
     *
     * @ORM\Column(name="last_activity", type="datetime")
     */
    private $last_activity;

    /**
     * @var String
     *
     * @ORM\Column(name="session_id", type="string")
     */
    private $session_id;


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
     * Set time
     *
     * @param \DateTime $last_activity
     *
     * @return Counter
     */
    public function setTime($last_activity)
    {
        $this->last_activity = $last_activity;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->last_activity;
    }

    /**
     * @return String
     */
    public function getSessionId()
    {
        return $this->session_id;
    }

    /**
     * @param String $session_id
     */
    public function setSessionId($session_id)
    {
        $this->session_id = $session_id;
    }
}

