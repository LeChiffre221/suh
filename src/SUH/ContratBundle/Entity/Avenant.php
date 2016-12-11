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
     * @var integer
     *
     * @ORM\Column(name="nbHeure", type="integer")
     */
    private $nbHeure;


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
     * Set dateEnvoiDRH
     *
     * @param \DateTime $dateEnvoiDRH
     *
     * @return Avenant
     */
    public function setDateEnvoiDRH($dateEnvoiDRH)
    {
        $dateEnvoiDRH = date("Y-m-d", strtotime(strtr($dateEnvoiDRH, '/', '-') ));

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
     * @return Avenant
     */
    public function setDateEnvoiEtudiant($dateEnvoiEtudiant)
    {

        $dateEnvoiEtudiant = date("Y-m-d", strtotime(strtr($dateEnvoiEtudiant, '/', '-') ));

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
