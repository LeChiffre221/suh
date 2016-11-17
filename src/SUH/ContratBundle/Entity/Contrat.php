<?php

namespace SUH\ContratBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contrat
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SUH\ContratBundle\Entity\ContratRepository")
 */
class Contrat
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
     * @ORM\ManyToOne(targetEntity="SUH\ContratBundle\Entity\EtudiantAidant")
     * @ORM\JoinColumn(nullable=false)
     */
    private $etudiantAidant;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbHeureInitiales", type="integer")
     */
    private $nbHeureInitiales;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebutContrat", type="date")
     */
    private $dateDebutContrat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateFinContrat", type="date")
     */
    private $dateFinContrat;

    /**
     * @var integer
     *
     * @ORM\Column(name="semestreConcerne", type="integer")
     */
    private $semestreConcerne;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateEnvoiDRH", type="date", nullable=true)
     */
    private $dateEnvoiDRH;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateEnvoiEtudiant", type="date", nullable=true)
     */
    private $dateEnvoiEtudiant;

    /**
     * @var boolean
     *
     * @ORM\Column(name="etablissementAvenant", type="boolean", nullable=true)
     */
    private $etablissementAvenant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateEnvoiAvenantDRH", type="date", nullable=true)
     */
    private $dateEnvoiAvenantDRH;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateEnvoiAvenantEtudiant", type="date", nullable=true)
     */
    private $dateEnvoiAvenantEtudiant;

    /**
     * @var string
     *
     * @ORM\Column(name="natureContrat", type="string", length=500)
     */
    private $natureContrat;


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
     * Set nbHeureInitiales
     *
     * @param integer $nbHeureInitiales
     *
     * @return Contrat
     */
    public function setNbHeureInitiales($nbHeureInitiales)
    {
        $this->nbHeureInitiales = $nbHeureInitiales;
    
        return $this;
    }

    /**
     * Get nbHeureInitiales
     *
     * @return integer
     */
    public function getNbHeureInitiales()
    {
        return $this->nbHeureInitiales;
    }

    /**
     * Set dateDebutContrat
     *
     * @param \DateTime $dateDebutContrat
     *
     * @return Contrat
     */
    public function setDateDebutContrat($dateDebutContrat)
    {
        $this->dateDebutContrat = $dateDebutContrat;
    
        return $this;
    }

    /**
     * Get dateDebutContrat
     *
     * @return \DateTime
     */
    public function getDateDebutContrat()
    {
        return $this->dateDebutContrat;
    }

    /**
     * Set dateFinContrat
     *
     * @param \DateTime $dateFinContrat
     *
     * @return Contrat
     */
    public function setDateFinContrat($dateFinContrat)
    {
        $this->dateFinContrat = $dateFinContrat;
    
        return $this;
    }

    /**
     * Get dateFinContrat
     *
     * @return \DateTime
     */
    public function getDateFinContrat()
    {
        return $this->dateFinContrat;
    }

    /**
     * Set semestreConcerne
     *
     * @param integer $semestreConcerne
     *
     * @return Contrat
     */
    public function setSemestreConcerne($semestreConcerne)
    {
        $this->semestreConcerne = $semestreConcerne;
    
        return $this;
    }

    /**
     * Get semestreConcerne
     *
     * @return integer
     */
    public function getSemestreConcerne()
    {
        return $this->semestreConcerne;
    }

    /**
     * Set dateEnvoiDRH
     *
     * @param \DateTime $dateEnvoiDRH
     *
     * @return Contrat
     */
    public function setDateEnvoiDRH($dateEnvoiDRH)
    {
        $this->dateEnvoiDRH = $dateEnvoiDRH;
    
        return $this;
    }

    /**
     * Get dateEnvoiDRH
     *
     * @return \DateTime
     */
    public function getDateEnvoiDRH()
    {
        return $this->dateEnvoiDRH;
    }

    /**
     * Set dateEnvoiEtudiant
     *
     * @param \DateTime $dateEnvoiEtudiant
     *
     * @return Contrat
     */
    public function setDateEnvoiEtudiant($dateEnvoiEtudiant)
    {
        $this->dateEnvoiEtudiant = $dateEnvoiEtudiant;
    
        return $this;
    }

    /**
     * Get dateEnvoiEtudiant
     *
     * @return \DateTime
     */
    public function getDateEnvoiEtudiant()
    {
        return $this->dateEnvoiEtudiant;
    }

    /**
     * Set etablissementAvenant
     *
     * @param boolean $etablissementAvenant
     *
     * @return Contrat
     */
    public function setEtablissementAvenant($etablissementAvenant)
    {
        $this->etablissementAvenant = $etablissementAvenant;
    
        return $this;
    }

    /**
     * Get etablissementAvenant
     *
     * @return boolean
     */
    public function getEtablissementAvenant()
    {
        return $this->etablissementAvenant;
    }

    /**
     * Set dateEnvoiAvenantDRH
     *
     * @param \DateTime $dateEnvoiAvenantDRH
     *
     * @return Contrat
     */
    public function setDateEnvoiAvenantDRH($dateEnvoiAvenantDRH)
    {
        $this->dateEnvoiAvenantDRH = $dateEnvoiAvenantDRH;
    
        return $this;
    }

    /**
     * Get dateEnvoiAvenantDRH
     *
     * @return \DateTime
     */
    public function getDateEnvoiAvenantDRH()
    {
        return $this->dateEnvoiAvenantDRH;
    }

    /**
     * Set dateEnvoiAvenantEtudiant
     *
     * @param \DateTime $dateEnvoiAvenantEtudiant
     *
     * @return Contrat
     */
    public function setDateEnvoiAvenantEtudiant($dateEnvoiAvenantEtudiant)
    {
        $this->dateEnvoiAvenantEtudiant = $dateEnvoiAvenantEtudiant;
    
        return $this;
    }

    /**
     * Get dateEnvoiAvenantEtudiant
     *
     * @return \DateTime
     */
    public function getDateEnvoiAvenantEtudiant()
    {
        return $this->dateEnvoiAvenantEtudiant;
    }

    /**
     * Set natureContrat
     *
     * @param string $natureContrat
     *
     * @return Contrat
     */
    public function setNatureContrat($natureContrat)
    {
        $this->natureContrat = $natureContrat;
    
        return $this;
    }

    /**
     * Get natureContrat
     *
     * @return string
     */
    public function getNatureContrat()
    {
        return $this->natureContrat;
    }

    /**
     * Set etudiantAidant
     *
     * @param \SUH\ContratBundle\Entity\EtudiantAidant $etudiantAidant
     *
     * @return Contrat
     */
    public function setEtudiantAidant(\SUH\ContratBundle\Entity\EtudiantAidant $etudiantAidant)
    {
        $this->etudiantAidant = $etudiantAidant;
    
        return $this;
    }

    /**
     * Get etudiantAidant
     *
     * @return \SUH\ContratBundle\Entity\EtudiantAidant
     */
    public function getEtudiantAidant()
    {
        return $this->etudiantAidant;
    }
}
