<?php

namespace SUH\ContratBundle\Entity;

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


    private $etudiantInformations;

    private $formation;

   /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

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
