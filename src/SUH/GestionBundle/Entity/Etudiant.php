<?php

namespace SUH\GestionBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Etudiant
 *
 * @ORM\Table(name="etudiant")
 * @ORM\Entity(repositoryClass="SUH\GestionBundle\Entity\EtudiantRepository")
 */
class Etudiant
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
     * @ORM\Column(name="numeroEtudiant", type="string", length=255, nullable=true)
     * @Assert\Type(type="string")
     */
    private $numeroEtudiant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateNaissance", type="date", nullable=true)
     */
    private $dateNaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="premiereInscription", type="string", nullable=true)
     * @Assert\Regex("/^[0-9]{4}[-][0-9]{4}$/")
     *
     */
    private $premiereInscription;

    /**
     * @ORM\OneToMany(targetEntity="SUH\GestionBundle\Entity\EtudiantEtudiantInformations",mappedBy="etudiant",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $listEtudiantInformations;

    /**
     * @ORM\OneToMany(targetEntity="SUH\GestionBundle\Entity\EtudiantFormation",mappedBy="etudiant",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $listEtudiantFormation;

    /**
     * @ORM\OneToMany(targetEntity="SUH\GestionBundle\Entity\EtudiantEtudiantHandicape",mappedBy="etudiant",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $listEtudiantHandicape;



    public function __construct($numeroEtudiant=null, $dateNaissance=null, $premiereInscription=null){

        $this->numeroEtudiant = $numeroEtudiant;
        $this->dateNaissance = $dateNaissance;
        if(isset($premiereInscription)){
            $this->premiereInscription = $premiereInscription;
        }
        $this->listEtudiantInformations=new ArrayCollection();
        $this->listEtudiantFormation=new ArrayCollection();
        $this->listEtudiantHandicape=new ArrayCollection();
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
     * Set numeroEtudiant
     *
     * @param string $numeroEtudiant
     *
     * @return Etudiant
     */
    public function setNumeroEtudiant($numeroEtudiant)
    {
        $this->numeroEtudiant = $numeroEtudiant;

        return $this;
    }

    /**
     * Get numeroEtudiant
     *
     * @return string
     */
    public function getNumeroEtudiant()
    {
        return $this->numeroEtudiant;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     *
     * @return Etudiant
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get premiereInscription
     *
     * @return string
     */
    public function getPremiereInscription()
    {
        return $this->premiereInscription;
    }

    /**
     * Set premiereInscription
     *
     * @param string $premiereInscription
     *
     * @return Etudiant
     */
    public function setPremiereInscription($premiereInscription)
    {
        $this->premiereInscription = $premiereInscription;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    public function addListEtudiantInformations($listEtudiantInformations)
    {
        $this->listEtudiantInformations[] = $listEtudiantInformations;
    }
    
    public function removeListEtudiantInformations($listEtudiantInformations)
    {
        $this->listEtudiantInformations->removeElement($listEtudiantInformations);
    }
    
    public function getListEtudiantInformations()
    {
        return $this->listEtudiantInformations;
    }

    public function addListEtudiantFormation($listEtudiantFormation)
    {
        $this->listEtudiantFormation[] = $listEtudiantFormation;
    }
    
    public function removeListEtudiantFormation($listEtudiantFormation)
    {
        $this->listEtudiantFormation->removeElement($listEtudiantFormation);
    }
    
    public function getListEtudiantFormation()
    {
        return $this->listEtudiantFormation;
    }

    public function addListEtudiantHandicape($listEtudiantHandicape)
    {
        $this->listEtudiantHandicape[] = $listEtudiantHandicape;
    }
    
    public function removeListEtudiantHandicape($listEtudiantHandicape)
    {
        $this->listEtudiantHandicape->removeElement($listEtudiantHandicape);
    }
    
    public function getListEtudiantHandicape()
    {
        return $this->listEtudiantHandicape;
    }

    /**
     * Add listEtudiantInformation
     *
     * @param \SUH\GestionBundle\Entity\EtudiantEtudiantInformations $listEtudiantInformation
     *
     * @return Etudiant
     */
    public function addListEtudiantInformation(\SUH\GestionBundle\Entity\EtudiantEtudiantInformations $listEtudiantInformation)
    {
        $this->listEtudiantInformations[] = $listEtudiantInformation;

        return $this;
    }

    /**
     * Remove listEtudiantInformation
     *
     * @param \SUH\GestionBundle\Entity\EtudiantEtudiantInformations $listEtudiantInformation
     */
    public function removeListEtudiantInformation(\SUH\GestionBundle\Entity\EtudiantEtudiantInformations $listEtudiantInformation)
    {
        $this->listEtudiantInformations->removeElement($listEtudiantInformation);
    }
}
