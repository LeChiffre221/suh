<?php

namespace SUH\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EtudiantEtudiantHandicape
 *
 * @ORM\Table(name="etudiantetudianthandicape")
 * @ORM\Entity(repositoryClass="SUH\GestionBundle\Entity\EtudiantEtudiantHandicapeRepository")
 */
class EtudiantEtudiantHandicape
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="SUH\GestionBundle\Entity\Etudiant",inversedBy="listEtudiantHandicape",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     */
    private $etudiant;

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="anneeScolaire", type="string", length=9)
     */
    private $anneeScolaire;

    /**
     * @ORM\ManyToOne(targetEntity="SUH\GestionBundle\Entity\EtudiantHandicape",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     */
    private $etudiantHandicape;

    /* ====================================================================== */
    /* ====================================================================== */
    /* ====================================================================== */
    
    public function __construct($etudiant,$anneeScolaire,$etudiantHandicape)
    {
        $this->etudiant=$etudiant;
        $this->anneeScolaire=$anneeScolaire;
        $this->etudiantHandicape=$etudiantHandicape;
    }
    
    //public function __construct(){}



    /**
     * Set etudiant
     *
     * @param string $etudiant
     *
     * @return EtudiantEtudiantHandicape
     */
    public function setEtudiant($etudiant)
    {
        $this->etudiant = $etudiant;
        $this->etudiant->addListEtudiantHandicape($this);

        return $this;
    }

    /**
     * Get etudiant
     *
     */
    public function getEtudiant()
    {
        return $this->etudiant;
    }

    /**
     * Set anneeScolaire
     *
     * @param string $anneeScolaire
     *
     * @return EtudiantEtudiantHandicape
     */
    public function setAnneeScolaire($anneeScolaire)
    {
        $this->anneeScolaire = $anneeScolaire;

        return $this;
    }

    /**
     * Get anneeScolaire
     *
     * @return string
     */
    public function getAnneeScolaire()
    {
        return $this->anneeScolaire;
    }

    /**
     * Set etudiantHandicape
     *
     * @param string $etudiantHandicape
     *
     * @return EtudiantEtudiantHandicape
     */
    public function setEtudiantHandicape($etudiantHandicape)
    {
        $this->etudiantHandicape = $etudiantHandicape;

        return $this;
    }

    /**
     * Get etudiantHandicape
     *
     * @return string
     */
    public function getEtudiantHandicape()
    {
        return $this->etudiantHandicape;
    }
}
