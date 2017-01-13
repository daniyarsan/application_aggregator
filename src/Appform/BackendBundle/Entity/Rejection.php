<?php

namespace Appform\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rejection
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Rejection
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="vendor", type="string", length=255)
     */
    protected $vendor;

    /**
     * @var string
     *
     * @ORM\Column(name="disciplines_list", type="array", nullable=true)
     */
    private $disciplinesList;

    /**
     * @var string
     *
     * @ORM\Column(name="specialties_list", type="array", nullable=true)
     */
    private $specialtiesList;

    /**
     * @var string
     *
     * @ORM\Column(name="reject_message", type="text")
     */
    protected $reject_message;


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
     * Set vendor
     *
     * @param string $vendor
     *
     * @return Rejection
     */
    public function setVendor($vendor)
    {
        $this->vendor = $vendor;

        return $this;
    }

    /**
     * Get vendor
     *
     * @return string
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * Set disciplinesList
     *
     * @param array $disciplinesList
     *
     * @return Rejection
     */
    public function setDisciplinesList($disciplinesList)
    {
        $this->disciplinesList = $disciplinesList;

        return $this;
    }

    /**
     * Get disciplinesList
     *
     * @return array
     */
    public function getDisciplinesList()
    {
        return $this->disciplinesList;
    }

    /**
     * Set specialtiesList
     *
     * @param array $specialtiesList
     *
     * @return Rejection
     */
    public function setSpecialtiesList($specialtiesList)
    {
        $this->specialtiesList = $specialtiesList;

        return $this;
    }

    /**
     * Get specialtiesList
     *
     * @return array
     */
    public function getSpecialtiesList()
    {
        return $this->specialtiesList;
    }

    /**
     * Set rejectMessage
     *
     * @param string $rejectMessage
     *
     * @return Rejection
     */
    public function setRejectMessage($rejectMessage)
    {
        $this->reject_message = $rejectMessage;

        return $this;
    }

    /**
     * Get rejectMessage
     *
     * @return string
     */
    public function getRejectMessage()
    {
        return $this->reject_message;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Rejection
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
