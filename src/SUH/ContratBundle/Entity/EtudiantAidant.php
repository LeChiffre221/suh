<?php

namespace SUH\ContratBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * EtudiantAidant
 *
 * @ORM\Table(name="etudiantaide")
 * @ORM\Entity(repositoryClass="SUH\ContratBundle\Entity\EtudiantAidantRepository")
 */
class EtudiantAidant
{



    /**
     * @var boolean
     *
     * @ORM\Column(name="certificatMedical", type="boolean", nullable=true)
     */
    private $certificatMedical;


    /**
     * Set certificatMedical
     *
     * @param boolean $certificatMedical
     *
     * @return EtudiantAidant
     */
    public function setCertificatMedical($certificatMedical)
    {
        $this->certificatMedical = $certificatMedical;

        return $this;
    }

    /**
     * Get certificatMedical
     *
     * @return boolean
     */
    public function getCertificatMedical()
    {
        return $this->certificatMedical;
    }


   /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

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
     * @var integer
     * @ORM\OneToOne(targetEntity="SUH\GestionBundle\Entity\Etudiant", cascade={"persist"})
     */
    private $etudiant;


    public function getEtudiant()
    {
      return $this->etudiant;
    }
    public function setEtudiant($etudiant){
      
      return $this->etudiant = $etudiant;
    }


    /**
     * @var integer
     * @ORM\OneToOne(targetEntity="SUH\GestionBundle\Entity\Formation", cascade={"persist"})
     */
    private $formation;


  public function getFormation()
  {
    return $this->formation;
  }
  public function setFormation($formation){
    
    return $this->formation = $formation;
  }


   /**
     * @var integer
     * @ORM\OneToOne(targetEntity="SUH\GestionBundle\Entity\EtudiantInformations", cascade={"persist"})
     */
    private $etudiantInformations;


  public function getEtudiantInformations()
  {
    return $this->etudiantInformations;
  }
  public function setEtudiantInformations($etudiantInformations){
    
    return $this->etudiantInformations = $etudiantInformations;
  }


  public function getEtudiantFormation()
  {
    return $this->formation;
  }
  public function setEtudiantFormation($formation){

    return $this->formation = $formation;

  }

}
