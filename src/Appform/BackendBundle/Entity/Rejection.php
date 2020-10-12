<?php

namespace Appform\BackendBundle\Entity;

use Appform\FrontendBundle\Entity\Discipline;
use Doctrine\ORM\Mapping as ORM;

/**
 * Rejection
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Appform\BackendBundle\Entity\RejectionRepository")
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
     * @ORM\Column(name="vendor", type="string", length=255, nullable=true)
     */
    protected $vendor;

    /**
     * @var string
     *
     * @ORM\Column(name="vendor_type", type="string", length=255, nullable=true)
     */
    protected $vendorType;

    /**
     * @ORM\ManyToMany(targetEntity="Appform\FrontendBundle\Entity\Discipline", inversedBy="disciplinesReject")
     * @ORM\JoinTable(name="disciplines_reject")
     */
    private $disciplinesList;

    /**
     * @ORM\ManyToMany(targetEntity="Appform\FrontendBundle\Entity\Specialty", inversedBy="disciplinesReject")
     * @ORM\JoinTable(name="specialties_reject")
     */
    private $specialtiesList;

    /**
     * @var string
     *
     * @ORM\Column(name="reject_message", type="text")
     */
    protected $reject_message;

    /**
     * @ORM\ManyToMany(targetEntity="Appform\FrontendBundle\Entity\Discipline", inversedBy="disciplinesHide")
     * @ORM\JoinTable(name="disciplines_hide")
     */
    private $disciplinesHide;

    /**
     * @ORM\ManyToMany(targetEntity="Appform\FrontendBundle\Entity\Specialty", inversedBy="specialtiesHide")
     * @ORM\JoinTable(name="speceialties_hide")
     */
    private $specialtiesHide;

    /**
     * @var text
     *
     * @ORM\Column(name="conversion_code", type="text", nullable=true)
    */
    private $conversionCode;

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

    /**
     * @return string
     */
    public function getDisciplinesHide()
    {
        return $this->disciplinesHide;
    }

    /**
     * @param string $disciplinesHide
     */
    public function setDisciplinesHide($disciplinesHide)
    {
        $this->disciplinesHide = $disciplinesHide;
    }

    /**
     * @return string
     */
    public function getSpecialtiesHide()
    {
        return $this->specialtiesHide;
    }

    /**
     * @param string $specialtiesHide
     */
    public function setSpecialtiesHide($specialtiesHide)
    {
        $this->specialtiesHide = $specialtiesHide;
    }

    /**
     * @return string
     */
    public function getVendorType()
    {
        return $this->vendorType;
    }

    /**
     * @param string $vendorType
     */
    public function setVendorType($vendorType)
    {
        $this->vendorType = $vendorType;
    }

    /**
     * @return text
     */
    public function getConversionCode()
    {
        return $this->conversionCode;
    }

    /**
     * @param text $conversionCode
     */
    public function setConversionCode($conversionCode)
    {
        $this->conversionCode = $conversionCode;
    }
}
