<?php

namespace SUH\GestionBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * EtudiantHandicape
 *
 * @ORM\Table(name="etudianthandicape")
 * @ORM\Entity(repositoryClass="SUH\GestionBundle\Entity\EtudiantHandicapeRepository")
 */
class EtudiantHandicape
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
     * @ORM\Column(name="qhandi", type="string", length=30,nullable=true)
     */
    private $qhandi;

    /**
     * @var string
     *
     * @ORM\Column(name="rqth", type="string",nullable=true)
     */
    private $rqth;

    /**
     * 
     * @ORM\OneToOne(targetEntity="SUH\GestionBundle\Entity\NotificationSavs",cascade={"persist"})
     * @ORM\JoinColumn(nullable=true,onDelete="CASCADE")
     */
    private $notificationSavs;

    /**
     * 
     * @ORM\ManyToMany(targetEntity="SUH\GestionBundle\Entity\AmenagementEtude",cascade={"persist"})
     * @ORM\JoinColumn(nullable=true,onDelete="CASCADE")
     */
    private $amenagementEtude;

    /**
     * @var string
     *
     * @ORM\Column(name="tauxInvalidite", type="text",nullable=true)
     */
    private $tauxInvalidite;

    /**
     * @var string
     *
     * @ORM\Column(name="suivi", type="string", length=30,nullable=true)
     */
    private $suivi;

    /**
     * @var string
     *
     * @ORM\Column(name="typeAllocations", type="string", length=255,nullable=true)
     */
    private $typeAllocations;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptifComplementaire", type="text",nullable=true)
     */
    private $descriptifComplementaire;

    /**
     * @var boolean
     *
     * @ORM\Column(name="demandeMdphEnCours", type="boolean",nullable=true)
     */
    private $demandeMdphEnCours;

    /**
     * @var boolean
     *
     * @ORM\Column(name="demandeRqthEnCours", type="boolean",nullable=true)
     */
    private $demandeRqthEnCours;

    /**
     * @var boolean
     *
     * @ORM\Column(name="demandeNotificationSavsEnCours", type="boolean",nullable=true)
     */
    private $demandeNotificationSavsEnCours;

    /**
     * @ORM\ManyToOne(targetEntity="SUH\GestionBundle\Entity\Mdph",cascade={"persist"})
     * @ORM\JoinColumn(nullable=true,onDelete="CASCADE")
     */
    private $mdph;
   
    /**
     * 
     * @ORM\ManyToMany(targetEntity="SUH\GestionBundle\Entity\Handicap",cascade={"persist"})
     * @ORM\JoinColumn(nullable=true,onDelete="CASCADE")
     */
    private $handicap;

    /**
     * @ORM\ManyToOne(targetEntity="SUH\GestionBundle\Entity\AideExamen",cascade={"persist"})
     * @ORM\JoinColumn(nullable=true,onDelete="CASCADE")
     */
    private $aideExamen;

    /* ====================================================================== */
    /* ====================================================================== */
    /* ====================================================================== */
    
    public function __construct($qhandi,$rqth,$notificationSavs,$amenagementEtude,
            $tauxInvalidite,$suivi,$typeAllocations,$descriptifComplementaire,$mdph,$handicap,$demandeMdphEnCours, $demandeRqthEnCours, $demandeNotificationSavsEnCours, $aideExamen)
    {
        $this->qhandi=$qhandi;
        $this->rqth=$rqth;
        $this->notificationSavs=$notificationSavs;
        $this->tauxInvalidite=$tauxInvalidite;
        $this->suivi=$suivi;
        $this->typeAllocations=$typeAllocations;
        $this->descriptifComplementaire=$descriptifComplementaire;
        $this->mdph=$mdph;
        $this->demandeMdphEnCours = $demandeMdphEnCours;
        $this->demandeRqthEnCours = $demandeRqthEnCours;
        $this->demandeNotificationSavsEnCours = $demandeNotificationSavsEnCours;
        $this->aideExamen=$aideExamen;
        $this->handicap=new ArrayCollection();
        $this->amenagementEtude=new ArrayCollection();
        if($handicap != null)
        {
            $this->addHandicap($handicap);
        }

        if($amenagementEtude != null)
        {
            $this->addAmenagementEtude($amenagementEtude);
        }
    }
    
    public function getAideExamen()
    {
        return $this->aideExamen;
    }

    public function setAideExamen($aideExamen)
    {
        $this->aideExamen = $aideExamen;
        
        return $this;
    }
    
    /**
     * Get amenagementEtude
     *
     * @return string 
     */
    public function getAmenagementEtude()
    {
        return $this->amenagementEtude;
    }

    public function addAmenagementEtude($amenagementEtude)
    {
        $this->amenagementEtude[] = $amenagementEtude;
        
        return $this;
    }

    public function removeAmenagementEtude($amenagementEtude)
    {
        $this->amenagementEtude->removeElement($amenagementEtude);
        
        return $this;
    }

    public function getHandicap()
    {
        return $this->handicap;
    }

    public function addHandicap($handicap)
    {
        $this->handicap[] = $handicap;
        
        return $this;
    }
    
    public function removeHandicap($handicap)
    {
        $this->handicap->removeElement($handicap);
        
        return $this;
    }
    
  
    public function getMdph()
    {
        return $this->mdph;
    }

    public function setMdph($mdph)
    {
        $this->mdph = $mdph;
        
        return $this;
    }
    
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
     * set id
     *
     * @param integer $id
     * @return EtudiantHandicape
     */
    public function setId($id)
    {
        $this->id=$id;
        
        return $this;
    }
    
    /**
     * Set qhandi
     *
     * @param string $qhandi
     * @return EtudiantHandicap
     */
    public function setQhandi($qhandi)
    {
        $this->qhandi = $qhandi;

        return $this;
    }

    /**
     * Get qhandi
     *
     * @return string 
     */
    public function getQhandi()
    {
        return $this->qhandi;
    }

    /**
     * Set rqth
     *
     * @param string $rqth
     * @return EtudiantHandicap
     */
    public function setRqth($rqth)
    {
        $this->rqth = $rqth;

        return $this;
    }

    /**
     * Get rqth
     *
     * @return string 
     */
    public function getRqth()
    {
        return $this->rqth;
    }


    /**
     * Set demandeMdphEnCours
     *
     * @param boolean $demandeMdphEnCours
     * @return EtudiantHandicap
     */
    public function setDemandeMdphEnCours($demandeMdphEnCours)
    {
        $this->demandeMdphEnCours = $demandeMdphEnCours;

        return $this;
    }

    /**
     * Get demandeMdphEnCours
     *
     * @return boolean 
     */
    public function getDemandeMdphEnCours()
    {
        return $this->demandeMdphEnCours;
    }

    /**
     * Set demandeRqthEnCours
     *
     * @param boolean $demandeRqthEnCours
     * @return EtudiantHandicap
     */
    public function setDemandeRqthEnCours($demandeRqthEnCours)
    {
        $this->demandeRqthEnCours = $demandeRqthEnCours;

        return $this;
    }

    /**
     * Get demandeRqthEnCours
     *
     * @return boolean 
     */
    public function getDemandeRqthEnCours()
    {
        return $this->demandeRqthEnCours;
    }

    /**
     * Set demandeNotificationSavsEnCours
     *
     * @param boolean $demandeNotificationSavsEnCours
     * @return EtudiantHandicap
     */
    public function setDemandeNotificationSavsEnCours($demandeNotificationSavsEnCours)
    {
        $this->demandeNotificationSavsEnCours = $demandeNotificationSavsEnCours;

        return $this;
    }

    /**
     * Get demandeNotificationSavsEnCours
     *
     * @return boolean 
     */
    public function getDemandeNotificationSavsEnCours()
    {
        return $this->demandeNotificationSavsEnCours;
    }

    /**
     * Set notificationSavs
     *
     * @param string $notificationSavs
     * @return EtudiantHandicap
     */
    public function setNotificationSavs($notificationSavs)
    {
        $this->notificationSavs = $notificationSavs;

        return $this;
    }

    /**
     * Get notificationSavs
     *
     * @return string 
     */
    public function getNotificationSavs()
    {
        return $this->notificationSavs;
    }

    /**
     * Set amenagementEtude
     *
     * @param string $amenagementEtude
     * @return EtudiantHandicap
     */
    public function setAmenagementEtude($amenagementEtude)
    {
        $this->amenagementEtude = $amenagementEtude;

        return $this;
    }

    

    /**
     * Set tauxInvalidite
     *
     * @param string $tauxInvalidite
     * @return EtudiantHandicap
     */
    public function setTauxInvalidite($tauxInvalidite)
    {
        $this->tauxInvalidite = $tauxInvalidite;

        return $this;
    }

    /**
     * Get tauxInvalidite
     *
     * @return string 
     */
    public function getTauxInvalidite()
    {
        return $this->tauxInvalidite;
    }

    /**
     * Set suivi
     *
     * @param string $suivi
     * @return EtudiantHandicap
     */
    public function setSuivi($suivi)
    {
        $this->suivi = $suivi;

        return $this;
    }

    /**
     * Get suivi
     *
     * @return string 
     */
    public function getSuivi()
    {
        return $this->suivi;
    }

    /**
     * Set descriptifComplementaire
     *
     * @param string $descriptifComplementaire
     * @return EtudiantHandicap
     */
    public function setDescriptifComplementaire($descriptifComplementaire)
    {
        $this->descriptifComplementaire = $descriptifComplementaire;

        return $this;
    }

    /**
     * Get descriptifComplementaire
     *
     * @return string 
     */
    public function getDescriptifComplementaire()
    {
        return $this->descriptifComplementaire;
    }

    /**
     * Set typeAllocations
     *
     * @param string $typeAllocations
     * @return EtudiantHandicap
     */
    public function setTypeAllocations($typeAllocations)
    {
        $this->typeAllocations = $typeAllocations;

        return $this;
    }

    /**
     * Get typeAllocations
     *
     * @return string 
     */
    public function getTypeAllocations()
    {
        return $this->typeAllocations;
    }



}
