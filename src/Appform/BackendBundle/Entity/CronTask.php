<?php

namespace Appform\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity
 * @UniqueEntity("name")
 */
class CronTask
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $commands;

    /**
     * @ORM\Column(type="integer", nullable=true, name="`interval`")
     */
    private $interval;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastrun;

    public function __toString() {
        return $this->name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getCommands()
    {
        return $this->commands;
    }

    public function setCommands($commands)
    {
        $this->commands = $commands;
        return $this;
    }

    public function getInterval()
    {
        return $this->interval;
    }

    public function setInterval($interval)
    {
        $this->interval = $interval;
        return $this;
    }

    public function getLastRun()
    {
        return $this->lastrun;
    }

    public function setLastRun($lastrun)
    {
        $this->lastrun = $lastrun;
        return $this;
    }
}
