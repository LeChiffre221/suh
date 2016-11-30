<?php

namespace SUH\ContratBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Avenant
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SUH\ContratBundle\Entity\AvenantRepository")
 */
class Avenant
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
     * @var array
     *
     * @ORM\Column(name="natureAvenant", type="array")
     */
    private $natureAvenant;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbHeure", type="integer")
     */
    private $nbHeure;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebutAvenant", type="date")
     */
    private $dateDebutAvenant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFinAvenant", type="date")
     */
    private $dateFinAvenant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateEnvoiDRH", type="date", nullable=true))
     */
    private $dateEnvoiDRH;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateEnvoiEtudiant", type="date", nullable=true))
     */
    private $dateEnvoiEtudiant;

    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="SUH\ContratBundle\Entity\Contrat", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $contrat;


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
     * Set natureAvenant
     *
     * @param array $natureAvenant
     *
     * @return Avenant
     */
    public function setNatureAvenant($natureAvenant)
    {
        $this->natureAvenant = $natureAvenant;
    
        return $this;
    }

    /**
     * Get natureAvenant
     *
     * @return array
     */
    public function getNatureAvenant()
    {
        return $this->natureAvenant;
    }

    /**
     * Set nbHeure
     *
     * @param integer $nbHeure
     *
     * @return Avenant
     */
    public function setNbHeure($nbHeure)
    {
        $this->nbHeure = $nbHeure;
    
        return $this;
    }

    /**
     * Get nbHeure
     *
     * @return integer
     */
    public function getNbHeure()
    {
        return $this->nbHeure;
    }

    /**
     * Set dateDebutAvenant
     *
     * @param \DateTime $dateDebutAvenant
     *
     * @return Avenant
     */
    public function setDateDebutAvenant($dateDebutAvenant)
    {

        $dateDebutAvenant = date("Y-m-d", strtotime(strtr($dateDebutAvenant, '/', '-') ));
        $this->dateDebutAvenant =  new DateTime($dateDebutAvenant);


    
        return $this;
    }

    /**
     * Get dateDebutAvenant
     *
     * @return \DateTime
     */
    public function getDateDebutAvenant()
    {
        if($this->dateDebutAvenant instanceof \DateTime){
            return $this->dateDebutAvenant->format('d/m/Y');
        }

        return $this->dateDebutAvenant;
    }

    /**
     * Set dateFinAvenant
     *
     * @param \DateTime $dateFinAvenant
     *
     * @return Avenant
     */
    public function setDateFinAvenant($dateFinAvenant)
    {

        $dateFinAvenant = date("Y-m-d", strtotime(strtr($dateFinAvenant, '/', '-') ));

        $this->dateFinAvenant = new DateTime($dateFinAvenant);

        return $this;
    }

    /**
     * Get dateFinAvenant
     *
     * @return \DateTime
     */
    public function getDateFinAvenant()
    {

        if($this->dateFinAvenant instanceof \DateTime){
            return $this->dateFinAvenant->format('d/m/Y');
        }
        return $this->dateFinAvenant;
    }

    /**
     * Set dateEnvoiDRH
     *
     * @param \DateTime $dateEnvoiDRH
     *
     * @return Avenant
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
     * @return Avenant
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
     * Set etudiantAidant
     *
     * @param \SUH\ContratBundle\Entity\EtudiantAidant $etudiantAidant
     *
     * @return Contrat
     */
    public function setContrat(\SUH\ContratBundle\Entity\Contrat $contrat)
    {
        $this->contrat = $contrat;

        return $this;
    }

    /**
     * Get etudiantAidant
     *
     * @return \SUH\ContratBundle\Entity\EtudiantAidant
     */
    public function getContrat()
    {
        return $this->contrat;
    }
}

