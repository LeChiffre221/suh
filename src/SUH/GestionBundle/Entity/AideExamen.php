<?php

namespace SUH\GestionBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

/**
 * AideExamen
 *
 * @ORM\Table(name="aideexamen")
 * @ORM\Entity(repositoryClass="SUH\GestionBundle\Entity\AideExamenRepository")
 */
class AideExamen
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
     * @ORM\Column(name="amenagementExamens", type="string",nullable=true)
     */
    private $amenagementExamens;

    /**
     * @var string
     *
     * @ORM\Column(name="avisMedical", type="text",nullable=true)
     */
    private $avisMedical;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tempsMajore", type="boolean",nullable=true)
     */
    private $tempsMajore;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateValidite", type="date",nullable=true)
     */
    private $dateValidite;

    /**
     * @var string
     *
     * @ORM\Column(name="dureeAvisMedical", type="text",nullable=true)
     */
    private $dureeAvisMedical;

    /**
     * 
     * @ORM\ManyToMany(targetEntity="SUH\GestionBundle\Entity\AdaptationDocuments",cascade={"persist"})
     * @ORM\JoinColumn(nullable=true,onDelete="CASCADE")
     */
    private $adaptationDocuments;

    /**
     * @ORM\ManyToOne(targetEntity="SUH\GestionBundle\Entity\Materiel",cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE",nullable=true)
     */
    private $materiel;

    /**
     * 
     * @ORM\ManyToMany(targetEntity="SUH\GestionBundle\Entity\Ordinateur",cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE",nullable=true)
     */
    private $ordinateur;

    /**
     * @ORM\ManyToOne(targetEntity="SUH\GestionBundle\Entity\SecretaireExamen",cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE",nullable=true)
     */
    private $secretaireExamen;

    /**
     * @ORM\ManyToOne(targetEntity="SUH\GestionBundle\Entity\DelocalisationExamen",cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE",nullable=true)
     */
    private $delocalisationExamen;

    
    /* ====================================================================== */
    /* ====================================================================== */
    /* ====================================================================== */
    
    public function __construct($amenagementExamen,$tempsMajore,$secretaireExamen,
            $delocalisationExamen, $avisMedical, $dateValidite,$dureeAvisMedical, $materiel)
    {
        $this->amenagementExamens=$amenagementExamen;
        $this->tempsMajore=$tempsMajore;
        $this->secretaireExamen=$secretaireExamen;
        $this->delocalisationExamen=$delocalisationExamen;
        $this->avisMedical = $avisMedical;
        $this->dateValidite= $dateValidite;
        $this->dureeAvisMedical=$dureeAvisMedical;
        $this->materiel = $materiel;
        $this->ordinateur=new ArrayCollection();
        $this->adaptationDocuments=new ArrayCollection();
    }

    public function getAvisMedical()
    {
        return $this->avisMedical;
    }

    public function setAvisMedical($avisMedical)
    {
        $this->avisMedical = $avisMedical;
        
        return $this;
    }

    public function getMateriel()
    {
        return $this->materiel;
    }

    public function setMateriel($materiel)
    {
        $this->materiel = $materiel;
        
        return $this;
    }

    public function getOrdinateur()
    {
        return $this->ordinateur;
    }

    public function addOrdinateur($ordinateur)
    {
        $this->ordinateur[] = $ordinateur;
        
        return $this;
    }
    
    public function removeOrdinateur($ordinateur)
    {
        $this->ordinateur->removeElement($ordinateur);
        
        return $this;
    }

    public function getAdaptationDocuments()
    {
        return $this->adaptationDocuments;
    }

    public function addAdaptationDocuments($adaptationDocuments)
    {
        $this->adaptationDocuments[] = $adaptationDocuments;
        
        return $this;
    }
    
    public function removeAdaptationDocuments($adaptationDocuments)
    {
        $this->adaptationDocuments->removeElement($adaptationDocuments);
        
        return $this;
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
     * Set amenagementExamens
     *
     * @param string $amenagementExamens
     * @return AideExamen
     */
    public function setAmenagementExamens($amenagementExamens)
    {
        $this->amenagementExamens = $amenagementExamens;

        return $this;
    }

    /**
     * Get amenagementExamens
     *
     * @return string 
     */
    public function getAmenagementExamens()
    {
        return $this->amenagementExamens;
    }

    /**
     * Set tempsMajore
     *
     * @param boolean $tempsMajore
     * @return AideExamen
     */
    public function setTempsMajore($tempsMajore)
    {
        $this->tempsMajore = $tempsMajore;

        return $this;
    }

    /**
     * Get tempsMajore
     *
     * @return boolean 
     */
    public function getTempsMajore()
    {
        return $this->tempsMajore;
    }


    public function setSecretaireExamen($secretaireExamen)
    {
        $this->secretaireExamen = $secretaireExamen;

        return $this;
    }

    public function getSecretaireExamen()
    {
        return $this->secretaireExamen;
    }

    

    

    public function setDelocalisationExamen($delocalisationExamen)
    {
        $this->delocalisationExamen = $delocalisationExamen;

        return $this;
    }


    public function getDelocalisationExamen()
    {
        return $this->delocalisationExamen;
    }

    /**
     * Set dateValidite
     *
     * @param \DateTime $dateValidite
     * @return AideExamen
     */
    public function setDateValidite($dateValidite)
    {
        $this->dateValidite = $dateValidite;

        return $this;
    }

    /**
     * Get dateValidite
     *
     * @return \DateTime 
     */
    public function getDateValidite()
    {
        return $this->dateValidite;
    }

    /**
     * Set dureeAvisMedical
     *
     * @param string $dureeAvisMedical
     * @return AideExamen
     */
    public function setDureeAvisMedical($dureeAvisMedical)
    {
        $this->dureeAvisMedical = $dureeAvisMedical;

        return $this;
    }

    /**
     * Get dureeAvisMedical
     *
     * @return string 
     */
    public function getDureeAvisMedical()
    {
        return $this->dureeAvisMedical;
    }

    /**
     * Add adaptationDocument
     *
     * @param \SUH\GestionBundle\Entity\AdaptationDocuments $adaptationDocument
     *
     * @return AideExamen
     */
    public function addAdaptationDocument(\SUH\GestionBundle\Entity\AdaptationDocuments $adaptationDocument)
    {
        $this->adaptationDocuments[] = $adaptationDocument;

        return $this;
    }

    /**
     * Remove adaptationDocument
     *
     * @param \SUH\GestionBundle\Entity\AdaptationDocuments $adaptationDocument
     */
    public function removeAdaptationDocument(\SUH\GestionBundle\Entity\AdaptationDocuments $adaptationDocument)
    {
        $this->adaptationDocuments->removeElement($adaptationDocument);
    }
}
