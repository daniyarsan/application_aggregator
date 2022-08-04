<?php

namespace Appform\FrontendBundle\Entity;

use Appform\BackendBundle\Entity\Rejection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Discipline
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Appform\FrontendBundle\Entity\DisciplineRepository")
 */

class Discipline
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
     * @ORM\Column(name="name", type="string", length=150)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=150, nullable=true)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="short", type="string", length=50, nullable=true)
     */
    private $short;

    /**
     * @var boolean
     *
     * @ORM\Column(name="hidden", type="boolean")
     */
    private $hidden;

    /**
     * @var integer
     *
     * @ORM\Column(name="ordering", type="integer", length=5, nullable=true)
     */
    private $order;

    /**
     * @var integer
     *
     * @ORM\Column(name="sjb_id", type="integer", length=5, nullable=true)
     */
    private $sjbId;

    /**
     * @return int
     */
    public function getSjbId()
    {
        return $this->sjbId;
    }

    /**
     * @param int $sjbId
     */
    public function setSjbId($sjbId)
    {
        $this->sjbId = $sjbId;
    }

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Appform\FrontendBundle\Entity\Redirect", mappedBy="specialty", cascade={"persist", "remove"})
     */
    private $redirects;

    /**
     * @var \Doctrine\Common\Collections\Collection|Specialty[]
     *
     * @ORM\ManyToMany(targetEntity="Appform\FrontendBundle\Entity\Specialty", mappedBy="disciplines", cascade={"persist"})
     */
    protected $specialties;


    public function __construct()
    {
        $this->redirects = new ArrayCollection();
        $this->specialties = new ArrayCollection();
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

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

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
    public function getShort()
    {
        return $this->short;
    }

    /**
     * @param string $short
     */
    public function setShort($short)
    {
        $this->short = $short;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return $this->hidden;
    }

    /**
     * @param bool $hidden
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return ArrayCollection
     */
    public function getRedirects()
    {
        return $this->redirects;
    }

    /**
     * @param ArrayCollection $redirects
     */
    public function setRedirects($redirects)
    {
        $this->redirects = $redirects;
    }


    /**
     * @param Specialty $specialty
     */
    public function addSpecialty(Specialty $specialty)
    {
        if ($this->specialties->contains($specialty)) {
            return;
        }

        $this->specialties->add($specialty);
        $specialty->addDiscipline($this);
    }

    /**
     * @param Specialty $specialty
     */
    public function removeSpecialty(Specialty $specialty)
    {
        if (!$this->specialties->contains($specialty)) {
            return;
        }

        $this->specialties->removeElement($specialty);
        $specialty->removeDiscipline($this);
    }

    /**
     * @return Specialty[]|\Doctrine\Common\Collections\Collection
     */
    public function getSpecialties()
    {
        return $this->specialties;
    }

    public function setSpecialties($specialties)
    {
        $this->specialties = $specialties;
        return true;
    }

}

