<?php

namespace SUH\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mdph
 *
 * @ORM\Table(name="mdph")
 * @ORM\Entity(repositoryClass="SUH\GestionBundle\Entity\MdphRepository")
 */
class Mdph
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
     * @ORM\Column(name="departementMdph", type="string", length=3,nullable=true)
     */
    private $departementMdph;

    


    /* ====================================================================== */
    /* ====================================================================== */
    /* ====================================================================== */

    public function __construct($departementMdph)
    {
        
        $this->departementMdph=$departementMdph;
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
     * Set departementMdph
     *
     * @param string $departementMdph
     * @return Mdph
     */
    public function setDepartementMdph($departementMdph)
    {
        $this->departementMdph = $departementMdph;

        return $this;
    }

    /**
     * Get departementMdph
     *
     * @return string 
     */
    public function getDepartementMdph()
    {
        return $this->departementMdph;
    }
}
