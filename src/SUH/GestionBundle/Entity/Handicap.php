<?php

namespace SUH\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Handicap
 *
 * @ORM\Table(name="handicap")
 * @ORM\Entity(repositoryClass="SUH\GestionBundle\Entity\HandicapRepository")
 */
class Handicap
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
     * @ORM\Column(name="nomHandicap", type="string", length=100,nullable=true)
     */
    private $nomHandicap;


    /* ====================================================================== */
    /* ====================================================================== */
    /* ====================================================================== */
    
    public function __construct($nomHandicap)
    {
        $this->nomHandicap=$nomHandicap;
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
     * Set nomHandicap
     *
     * @param string $nomHandicap
     * @return Handicap
     */
    public function setNomHandicap($nomHandicap)
    {
        $this->nomHandicap = $nomHandicap;

        return $this;
    }

    /**
     * Get nomHandicap
     *
     * @return string 
     */
    public function getNomHandicap()
    {
        return $this->nomHandicap;
    }
}
