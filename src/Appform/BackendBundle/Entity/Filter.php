<?php

namespace Appform\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Filter
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Appform\BackendBundle\Entity\FilterRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Filter
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
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var array
     *
     * @ORM\Column(name="userIds", type="array")
     */
    private $userIds;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    public function __toString() {
        return $this->name;
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

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set userIds
     *
     * @param array $userIds
     * @return Filter
     */
    public function setUserIds($userIds)
    {
        $this->userIds = $userIds;

        return $this;
    }

    /**
     * Get userIds
     *
     * @return array
     */
    public function getUserIds()
    {
        return $this->userIds;
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
}
