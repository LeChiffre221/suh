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
     * @var integer
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="SUH\GestionBundle\Entity\Etudiant", cascade={"persist"})
     */
    private $etudiant;


    /**
     * @var boolean
     *
     * @ORM\Column(name="certificatMedical", type="boolean", nullable=true)
     */
    private $certificatMedical;

    private $etudiantInformations;

    private $formation;

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


  public function getEtudiant()
  {
    return $this->etudiant;
  }
  public function setEtudiant($etudiant){
    $this->etudiant = $etudiant;
    return $this;
  }

  public function getEtudiantInformations()
  {
    return $this->etudiantInformations;
  }
  public function setEtudiantInformations($etudiantInformations){
    $this->etudiantInformations = $etudiantInformations;
    return $this;
  }

  public function getEtudiantFormation()
  {
    return $this->formation;
  }
  public function setEtudiantFormation($formation){
    $this->formation = $formation;
    return $this;
  }

}
