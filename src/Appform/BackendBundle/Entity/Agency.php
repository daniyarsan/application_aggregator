<?php

namespace Appform\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Agency
 *
 * @ORM\Table(name="Agency", indexes={@ORM\Index(name="IDX_776CC3D056AC33D", columns={"agencyGroup_id"})})
 * @ORM\Entity
 */
class Agency
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="Url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="Description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="ShortDescription", type="text", nullable=true)
     */
    private $shortdescription;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Active", type="boolean", nullable=false)
     */
    private $active;

    /**
     * @var \Agencygroup
     *
     * @ORM\ManyToOne(targetEntity="Agencygroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="agencyGroup_id", referencedColumnName="id")
     * })
     */
    private $agencygroup;


}

