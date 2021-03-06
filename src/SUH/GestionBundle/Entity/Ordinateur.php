<?php

namespace SUH\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ordinateur
 *
 * @ORM\Table(name="ordinateur")
 * @ORM\Entity(repositoryClass="SUH\GestionBundle\Entity\OrdinateurRepository")
 */
class Ordinateur
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
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /* ====================================================================== */
    /* ====================================================================== */
    /* ====================================================================== */
    
    public function __construct($type)
    {
        $this->type=$type;
    }
    
    //public function __construct(){}


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
     * Set type
     *
     * @param string $type
     *
     * @return Ordinateur
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
