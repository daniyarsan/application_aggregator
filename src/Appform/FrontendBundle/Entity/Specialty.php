<?php

namespace Appform\FrontendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Specialty
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Appform\FrontendBundle\Entity\SpecialtyRepository")
 */
class Specialty
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
     * @ORM\Column(name="short", type="string", length=50)
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
     * @ORM\Column(name="ordering", type="integer", length=5)
     */
    private $order;

    /**
     * @var integer
     *
     * @ORM\Column(name="sjb_id", type="integer", length=5)
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
     * @var \Doctrine\Common\Collections\Collection|Discipline[]
     *
     * @ORM\ManyToMany(targetEntity="Appform\FrontendBundle\Entity\Discipline", inversedBy="specialties", cascade={"persist"})
     * @ORM\JoinTable(
     *  name="specialty_discipline",
     *  joinColumns={
     *      @ORM\JoinColumn(name="specialty_id", referencedColumnName="id")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="discipline_id", referencedColumnName="id")
     *  }
     * )
     */
    protected $disciplines;


    public function __construct()
    {
        $this->redirects = new ArrayCollection();
        $this->disciplines = new ArrayCollection();
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
     * @param \Doctrine\Common\Collections\ArrayCollection $redirects
     */
    public function setRedirects($redirects)
    {
        $this->redirects = $redirects;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection|Redirect[]
     */
    public function getRedirects()
    {
        return $this->redirects;
    }

    /**
     * @param Discipline $discipline
     */
    public function addDiscipline(Discipline $discipline)
    {
        if ($this->disciplines->contains($discipline)) {
            return;
        }

        $this->disciplines->add($discipline);
        $discipline->addSpecialty($this);
    }

    /**
     * @param Discipline $discipline
     */
    public function removeDiscipline(Discipline $discipline)
    {
        if (!$this->disciplines->contains($discipline)) {
            return;
        }

        $this->disciplines->removeElement($discipline);
        $discipline->removeSpecialty($this);
    }
}

