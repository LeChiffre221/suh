<?php

namespace SUH\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EtudiantEtudiantInformations
 *
 * @ORM\Table(name="etudiantetudiantinformations")
 * @ORM\Entity(repositoryClass="SUH\GestionBundle\Entity\EtudiantEtudiantInformationsRepository")
 */
class EtudiantEtudiantInformations
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="SUH\GestionBundle\Entity\Etudiant",inversedBy="listEtudiantInformations",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     */
    private $etudiant;

    /**
     * @ORM\Id
     * @ORM\Column(name="anneeScolaire", type="string", length=9)
     */
    private $anneeScolaire;

    /**
     * @ORM\ManyToOne(targetEntity="SUH\GestionBundle\Entity\EtudiantInformations",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     */
    private $etudiantInformations;

    public function __construct($etudiant,$anneeScolaire,$etudiantInformations)
    {
        $this->etudiant=$etudiant;
        $this->anneeScolaire=$anneeScolaire;
        $this->etudiantInformations=$etudiantInformations;
    }
    
    //public function __construct(){}
 
    /**
     * Set etudiant
     *
     * @param string $etudiant
     *
     * @return EtudiantEtudiantInformations
     */
    public function setEtudiant($etudiant)
    {
        $this->etudiant = $etudiant;

        return $this;
    }

    /**
     * Get etudiant
     *
     * @return string
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
     * @return EtudiantEtudiantInformations
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
     * Set etudiantInformations
     *
     * @param string $etudiantInformations
     *
     * @return EtudiantEtudiantInformations
     */
    public function setEtudiantInformations($etudiantInformations)
    {
        $this->etudiantInformations = $etudiantInformations;

        return $this;
    }

    /**
     * Get etudiantInformations
     *
     * @return string
     */
    public function getEtudiantInformations()
    {
        return $this->etudiantInformations;
    }
}
