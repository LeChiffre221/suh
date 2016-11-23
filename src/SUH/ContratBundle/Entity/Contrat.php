<?php

namespace SUH\ContratBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @var string
     *
     * @ORM\Column(name="natureContrat", type="string", length=500)
     * @Assert\Type(type="string")
     */
    private $natureContrat;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbHeureInitiales", type="integer")
     * @Assert\Regex("/[0-9]+/")
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
     * @Assert\Regex("/1|2{1}/")
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
     * @Assert\Type(type="boolean")
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
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     * @Assert\Type(type="boolean")
     */
    private $active;

    /**
     * @ORM\ManyToOne(targetEntity="SUH\ContratBundle\Entity\EtudiantAidant")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $etudiantAidant;


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
        $dateDebutContrat = date("Y-m-d", strtotime(strtr($dateDebutContrat, '/', '-') ));
        $this->dateDebutContrat =  new DateTime($dateDebutContrat);
    
        return $this;
    }

    /**
     * Get dateDebutContrat
     *
     * @return \DateTime
     */
    public function getDateDebutContrat()
    {

        if($this->dateDebutContrat instanceof \DateTime){
            return $this->dateDebutContrat->format('d/m/Y');
        }
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
        $dateFinContrat = date("Y-m-d", strtotime(strtr($dateFinContrat, '/', '-') ));

        $this->dateFinContrat = new DateTime($dateFinContrat);
    
        return $this;
    }

    /**
     * Get dateFinContrat
     *
     * @return \DateTime
     */
    public function getDateFinContrat()
    {

        if($this->dateFinContrat instanceof \DateTime){
            return $this->dateFinContrat->format('d/m/Y');
        }
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
        $dateEnvoiDRH= date("Y-m-d", strtotime(strtr($dateEnvoiDRH, '/', '-') ));
        $this->dateEnvoiDRH = new DateTime($dateEnvoiDRH);
    
        return $this;
    }

    /**
     * Get dateEnvoiDRH
     *
     * @return \DateTime
     */
    public function getDateEnvoiDRH()
    {

        if($this->dateEnvoiDRH instanceof \DateTime){
            return $this->dateEnvoiDRH->format('d/m/Y');
        }
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
        $dateEnvoiEtudiant= date("Y-m-d", strtotime(strtr($dateEnvoiEtudiant, '/', '-') ));
        $this->dateEnvoiEtudiant = new DateTime($dateEnvoiEtudiant);
    
        return $this;
    }

    /**
     * Get dateEnvoiEtudiant
     *
     * @return \DateTime
     */
    public function getDateEnvoiEtudiant()
    {
        if($this->dateEnvoiEtudiant instanceof \DateTime){
            return $this->dateEnvoiEtudiant->format('d/m/Y');
        }
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
        $dateEnvoiAvenantDRH= date("Y-m-d", strtotime(strtr($dateEnvoiAvenantDRH, '/', '-') ));
        $this->dateEnvoiAvenantDRH = new DateTime($dateEnvoiAvenantDRH);
    
        return $this;
    }

    /**
     * Get dateEnvoiAvenantDRH
     *
     * @return \DateTime
     */
    public function getDateEnvoiAvenantDRH()
    {
        if($this->dateEnvoiAvenantDRH instanceof \DateTime){
            return $this->dateEnvoiAvenantDRH->format('d/m/Y');
        }
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

        $dateEnvoiAvenantEtudiant= date("Y-m-d", strtotime(strtr($dateEnvoiAvenantEtudiant, '/', '-') ));
        $this->dateEnvoiAvenantEtudiant = new DateTime($dateEnvoiAvenantEtudiant);
    
        return $this;
    }

    /**
     * Get dateEnvoiAvenantEtudiant
     *
     * @return \DateTime
     */
    public function getDateEnvoiAvenantEtudiant()
    {

        if($this->dateEnvoiAvenantEtudiant instanceof \DateTime){
            return $this->dateEnvoiAvenantEtudiant->format('d/m/Y');
        }
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

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Contrat
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }
}
