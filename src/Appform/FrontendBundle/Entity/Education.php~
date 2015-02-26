<?php

namespace Appform\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Education
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Education
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
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="degree", type="string", length=100)
     */
    private $degree;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=100)
     */
    private $location;

    /**
     * @var string
     *
     * @ORM\Column(name="graduationDate", type="datetime")
     */
    private $graduationDate;

    /**
     * @var string
     *
     * @ORM\Column(name="courseOfStudy", type="string", length=100)
     */
    private $courseOfStudy;

    /**
     * @ORM\OneToOne(targetEntity="Applicant")
     * @ORM\JoinColumn(name="applicant_id", referencedColumnName="id")
     **/
    private $applicant;
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
     * Set name
     *
     * @param string $name
     * @return Education
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
     * Set degree
     *
     * @param string $degree
     * @return Education
     */
    public function setDegree($degree)
    {
        $this->degree = $degree;

        return $this;
    }

    /**
     * Get degree
     *
     * @return string 
     */
    public function getDegree()
    {
        return $this->degree;
    }

    /**
     * Set location
     *
     * @param string $location
     * @return Education
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set graduationDate
     *
     * @param \DateTime $graduationDate
     * @return Education
     */
    public function setGraduationDate($graduationDate)
    {
        $this->graduationDate = $graduationDate;

        return $this;
    }

    /**
     * Get graduationDate
     *
     * @return \DateTime 
     */
    public function getGraduationDate()
    {
        return $this->graduationDate;
    }

    /**
     * Set courseOfStudy
     *
     * @param string $courseOfStudy
     * @return Education
     */
    public function setCourseOfStudy($courseOfStudy)
    {
        $this->courseOfStudy = $courseOfStudy;

        return $this;
    }

    /**
     * Get courseOfStudy
     *
     * @return string 
     */
    public function getCourseOfStudy()
    {
        return $this->courseOfStudy;
    }

    /**
     * Set applicant
     *
     * @param \Appform\FrontendBundle\Entity\Applicant $applicant
     * @return Education
     */
    public function setApplicant(\Appform\FrontendBundle\Entity\Applicant $applicant = null)
    {
        $this->applicant = $applicant;

        return $this;
    }

    /**
     * Get applicant
     *
     * @return \Appform\FrontendBundle\Entity\Applicant 
     */
    public function getApplicant()
    {
        return $this->applicant;
    }
}
