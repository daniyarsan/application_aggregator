<?php

namespace Appform\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Filter
 *
 * 
 * @ORM\Entity(repositoryClass="Appform\FrontendBundle\Entity\FilterRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Filter
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
     * @var array
     *
     * @ORM\Column(type="array", nullable=true, name="userIds")
     */
    private $userIds;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true, name="created")
     */
    private $created;

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
